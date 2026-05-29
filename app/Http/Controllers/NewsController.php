<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Feedback;
use App\Models\User;
use App\Helpers\FeedbackComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * News listing — ordered by published_at date by default.
     * If search yields no DB results, silently fetches from API.
     */
    public function index(Request $request)
    {
        // Default to English if no language selected
        if (!$request->has('language') && !$request->ajax()) {
            return redirect()->route('news.index', array_merge($request->all(), ['language' => 'English']));
        }

        $query = News::query();

        $searchTerm = $request->input('search');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($searchTerm) {
                $words = array_filter(explode(' ', $searchTerm));
                foreach ($words as $word) {
                    $q->where(function ($subQ) use ($word) {
                        $subQ->where('title', 'like', '%' . $word . '%')
                             ->orWhere('content', 'like', '%' . $word . '%');
                    });
                }
            });
        }

        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('language')) $query->where('language', $request->language);
        if ($request->filled('source_type')) $query->where('source_type', $request->source_type);

        // Sorting
        $sortBy = $request->input('sort', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldestPublished();
                break;
            case 'most_feedback':
                $query->withCount('feedbacks')->orderByDesc('feedbacks_count');
                break;
            case 'highest_trust':
                $query->withAvg('feedbacks', 'trust_score')->orderByDesc('feedbacks_avg_trust_score');
                break;
            case 'title_az':
                $query->orderBy('title', 'asc');
                break;
            case 'title_za':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latestPublished();
                break;
        }

        $news = $query->paginate(12);
        $categories = News::distinct()->pluck('category')->filter()->sort()->values();
        $languages = News::distinct()->pluck('language')->filter()->sort()->values();

        $searching = false;
        $autoFetched = false;

        if ($request->filled('search')) {
            $needsImport = false;
            if ($news->total() < 3) {
                $needsImport = true;
            } else {
                $recentCount = News::where(function ($q) use ($searchTerm) {
                        $words = array_filter(explode(' ', $searchTerm));
                        foreach ($words as $word) {
                            $q->where(function ($subQ) use ($word) {
                                $subQ->where('title', 'like', '%' . $word . '%')
                                     ->orWhere('content', 'like', '%' . $word . '%');
                            });
                        }
                    })
                    ->when($request->category, fn($q) => $q->where('category', $request->category))
                    ->when($request->language, fn($q) => $q->where('language', $request->language))
                    ->where('created_at', '>=', Carbon::now()->subDay())
                    ->count();
                if ($recentCount == 0) {
                    $needsImport = true;
                }
            }

            if ($needsImport) {
                $userLang = $request->input('language', 'English');
                $imported = $this->fetchAndInsertFromApi($searchTerm, $request->input('category'), $userLang, true, 3);
                
                if ($imported > 0) {
                    $autoFetched = true;
                    // Refresh query
                    $query = News::query();
                    $query->where(function ($q) use ($searchTerm) {
                        $words = array_filter(explode(' ', $searchTerm));
                        foreach ($words as $word) {
                            $q->where(function ($subQ) use ($word) {
                                $subQ->where('title', 'like', '%' . $word . '%')
                                     ->orWhere('content', 'like', '%' . $word . '%');
                            });
                        }
                    });
                    if ($request->filled('category')) $query->where('category', $request->category);
                    if ($request->filled('language')) $query->where('language', $request->language);
                    if ($request->filled('source_type')) $query->where('source_type', $request->source_type);
                    
                    switch ($sortBy) {
                        case 'oldest': $query->oldestPublished(); break;
                        case 'most_feedback': $query->withCount('feedbacks')->orderByDesc('feedbacks_count'); break;
                        case 'highest_trust': $query->withAvg('feedbacks', 'trust_score')->orderByDesc('feedbacks_avg_trust_score'); break;
                        case 'title_az': $query->orderBy('title', 'asc'); break;
                        case 'title_za': $query->orderBy('title', 'desc'); break;
                        default: $query->latestPublished(); break;
                    }
                    
                    $news = $query->paginate(12);
                    $categories = News::distinct()->pluck('category')->filter()->sort()->values();
                    $languages = News::distinct()->pluck('language')->filter()->sort()->values();
                }
            }
        }

        if ($request->ajax()) {
            $html = '';
            foreach ($news as $item) {
                $html .= view('partials.news-card', ['item' => $item])->render();
            }
            return response()->json(['html' => $html, 'hasMore' => $news->hasMorePages()]);
        }

        $allLanguages = collect(News::$languageMap)->values()->merge($languages)->unique()->sort()->values();
        $allCategories = collect(News::$categories)->map(fn($c) => ucfirst($c))->merge($categories)->unique()->sort()->values();

        return view('news.index', compact('news', 'categories', 'languages', 'allLanguages', 'allCategories', 'sortBy', 'searching', 'autoFetched'));
    }

    public function show($id)
    {
        $news = News::with(['feedbacks.user'])->findOrFail($id);
        $avgTrust = $news->feedbacks()->avg('trust_score');
        $avgClarity = $news->feedbacks()->avg('clarity_score');
        $feedbackCount = $news->feedbacks()->count();

        $sentiments = [
            'Positive' => $news->feedbacks()->where('sentiment', 'Positive')->count(),
            'Neutral'  => $news->feedbacks()->where('sentiment', 'Neutral')->count(),
            'Negative' => $news->feedbacks()->where('sentiment', 'Negative')->count(),
        ];

        $relatedNews = News::where('category', $news->category)
            ->where('id', '!=', $news->id)->latestPublished()->take(4)->get();

        return view('news.show', compact('news', 'avgTrust', 'avgClarity', 'feedbackCount', 'sentiments', 'relatedNews'));
    }

    /** Admin: single fetch (form POST) */
    public function fetchFromApi(Request $request)
    {
        $imported = $this->fetchAndInsertFromApi(null, $request->input('category', 'politics'), $request->input('language', 'en'));
        if ($imported > 0) return redirect()->back()->with('success', "Imported {$imported} articles.");
        return redirect()->back()->with('error', 'No new articles found or API limit reached.');
    }

    /** Admin: AJAX fetch for bulk */
    public function fetchFromApiAjax(Request $request)
    {
        $imported = $this->fetchAndInsertFromApi(null, $request->input('category', 'politics'), $request->input('language', 'en'));
        return response()->json(['imported' => $imported, 'category' => $request->input('category'), 'language' => $request->input('language')]);
    }

    /**
     * Core: Fetch from NewsData.io, insert with dedup, generate category-based feedback.
     */
    public function fetchAndInsertFromApi(?string $query = null, ?string $category = null, ?string $language = null, bool $isUserSearch = false, ?int $limit = null): int
    {
        $apiKey = $isUserSearch ? 'pub_c1f05f18da274d66bc1cbaae59f72a81' : config('services.newsdata.key');
        if (empty($apiKey)) return 0;

        $langCode = $language;
        $langMap = array_flip(News::$languageMap);
        if ($language && isset($langMap[$language])) $langCode = $langMap[$language];

        $params = ['apikey' => $apiKey, 'country' => 'in'];
        $params['language'] = ($langCode && strlen($langCode) <= 3) ? $langCode : 'en';
        if ($category) {
            $cat = strtolower($category);
            if (in_array($cat, News::$categories)) $params['category'] = $cat;
        }
        if ($query) $params['q'] = $query;

        try {
            $response = Http::timeout(15)->get('https://newsdata.io/api/1/latest', $params);
            if (!$response->successful()) return 0;

            $articles = $response->json('results') ?? [];
            $imported = 0;

            foreach ($articles as $article) {
                if (empty($article['title'])) continue;

                $articleId = $article['article_id'] ?? md5($article['title']);
                if (News::where('article_id', $articleId)->exists()) continue;
                if (News::where('title', $article['title'])->exists()) continue;

                $content = $article['description'] ?? $article['content'] ?? $article['title'];
                if (isset($article['content']) && strlen($article['content']) > strlen($content)) $content = $article['content'];

                $newsLang = News::languageName($article['language'] ?? 'en');
                $newsCat = 'General';
                if (!empty($article['category']) && is_array($article['category'])) {
                    $newsCat = ucfirst($article['category'][0]);
                } elseif ($category) {
                    $newsCat = ucfirst($category);
                }

                // Parse published date from API
                $publishedAt = null;
                if (!empty($article['pubDate'])) {
                    try { $publishedAt = Carbon::parse($article['pubDate']); } catch (\Exception $e) {}
                }

                $newsItem = News::create([
                    'title'        => $article['title'],
                    'content'      => $content,
                    'category'     => $newsCat,
                    'language'     => $newsLang,
                    'source'       => $article['source_name'] ?? $article['source_id'] ?? 'Unknown',
                    'image'        => $article['image_url'] ?? null,
                    'article_id'   => $articleId,
                    'source_url'   => $article['link'] ?? null,
                    'source_type'  => 'api',
                    'published_at' => $publishedAt,
                ]);

                $this->generateDummyFeedback($newsItem);
                $imported++;
                
                if ($limit && $imported >= $limit) {
                    break;
                }
            }

            return $imported;
        } catch (\Exception $e) {
            Log::error('NewsData.io fetch error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Generate 5-25 unique category-based dummy feedbacks per API article.
     * Uses 500+ unique comments from FeedbackComments helper.
     */
    private function generateDummyFeedback(News $news): void
    {
        $userIds = User::where('role', 'user')->pluck('id')->toArray();
        if (empty($userIds)) return;

        $sentiments = ['Positive', 'Negative'];
        $biasLevels = ['Low', 'Medium', 'High'];

        $numFeedback = rand(5, min(25, count($userIds)));
        shuffle($userIds);
        $selectedUsers = array_slice($userIds, 0, $numFeedback);

        // Get unique category-specific comments
        $comments = FeedbackComments::getComments($news->category, $numFeedback + 5);

        foreach ($selectedUsers as $i => $userId) {
            $comment = $comments[$i] ?? FeedbackComments::getRandom($news->category);

            Feedback::create([
                'user_id'       => $userId,
                'news_id'       => $news->id,
                'trust_score'   => rand(2, 5),
                'clarity_score' => rand(2, 5),
                'bias_level'    => $biasLevels[array_rand($biasLevels)],
                'sentiment'     => $sentiments[array_rand($sentiments)],
                'comment'       => $comment,
            ]);
        }
    }

    /** Scheduled hourly fetch */
    public static function scheduledFetch(): int
    {
        $controller = new self();
        $total = 0;
        foreach (['en', 'hi'] as $lang) {
            foreach (['politics', 'business', 'technology', 'health', 'education', 'science', 'sports', 'environment'] as $cat) {
                $total += $controller->fetchAndInsertFromApi(null, $cat, $lang);
                usleep(500000);
            }
        }
        Log::info("Scheduled fetch: {$total} imported");
        return $total;
    }
}
