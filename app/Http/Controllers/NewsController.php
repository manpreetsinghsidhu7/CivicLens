<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    /**
     * Display news listing with search, filter, and infinite scroll
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by language
        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        $news = $query->latest()->paginate(12);
        $categories = News::distinct()->pluck('category');
        $languages = News::distinct()->pluck('language');

        // For AJAX infinite scroll requests, return JSON
        if ($request->ajax()) {
            $html = '';
            foreach ($news as $item) {
                $html .= view('partials.news-card', ['item' => $item])->render();
            }
            return response()->json([
                'html'    => $html,
                'hasMore' => $news->hasMorePages(),
            ]);
        }

        return view('news.index', compact('news', 'categories', 'languages'));
    }

    /**
     * Show a single news article with feedback
     */
    public function show($id)
    {
        $news = News::with(['feedbacks.user'])->findOrFail($id);
        $avgTrust = $news->feedbacks()->avg('trust_score');
        $avgClarity = $news->feedbacks()->avg('clarity_score');
        $feedbackCount = $news->feedbacks()->count();

        // Sentiment distribution
        $sentiments = [
            'Positive' => $news->feedbacks()->where('sentiment', 'Positive')->count(),
            'Neutral'  => $news->feedbacks()->where('sentiment', 'Neutral')->count(),
            'Negative' => $news->feedbacks()->where('sentiment', 'Negative')->count(),
        ];

        return view('news.show', compact('news', 'avgTrust', 'avgClarity', 'feedbackCount', 'sentiments'));
    }

    /**
     * Fetch news from NewsData.io API
     */
    public function fetchFromApi()
    {
        $apiKey = config('services.newsdata.key');

        try {
            $response = Http::get('https://newsdata.io/api/1/latest', [
                'apikey'   => $apiKey,
                'country'  => 'in',
                'language' => 'en',
                'category' => 'politics',
            ]);

            if ($response->successful()) {
                $articles = $response->json('results') ?? [];
                $imported = 0;

                foreach ($articles as $article) {
                    if (empty($article['title']) || empty($article['description'])) continue;

                    News::updateOrCreate(
                        ['title' => $article['title']],
                        [
                            'content'  => $article['description'] ?? $article['title'],
                            'category' => $article['category'][0] ?? 'Politics',
                            'language' => $article['language'] ?? 'English',
                            'source'   => $article['source_name'] ?? $article['source_id'] ?? 'Unknown',
                            'image'    => $article['image_url'] ?? null,
                        ]
                    );
                    $imported++;
                }

                return redirect()->back()->with('success', "Successfully imported {$imported} news articles from NewsData.io");
            }

            return redirect()->back()->with('error', 'Failed to fetch from NewsData.io API.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'API Error: ' . $e->getMessage());
        }
    }
}
