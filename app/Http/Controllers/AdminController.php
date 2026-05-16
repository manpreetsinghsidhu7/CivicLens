<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin dashboard with analytics overview
     */
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalNews = News::count();
        $totalFeedback = Feedback::count();
        $avgTrust = Feedback::avg('trust_score');

        // Sentiment distribution for Chart.js
        $sentiments = [
            'Positive' => Feedback::where('sentiment', 'Positive')->count(),
            'Neutral'  => Feedback::where('sentiment', 'Neutral')->count(),
            'Negative' => Feedback::where('sentiment', 'Negative')->count(),
        ];

        // Bias distribution
        $biasLevels = [
            'Low'    => Feedback::where('bias_level', 'Low')->count(),
            'Medium' => Feedback::where('bias_level', 'Medium')->count(),
            'High'   => Feedback::where('bias_level', 'High')->count(),
        ];

        // Average trust & clarity per news (top 10)
        $newsWithScores = News::withAvg('feedbacks', 'trust_score')
            ->withAvg('feedbacks', 'clarity_score')
            ->withCount('feedbacks')
            ->whereHas('feedbacks')
            ->orderByDesc('feedbacks_count')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalNews', 'totalFeedback', 'avgTrust',
            'sentiments', 'biasLevels', 'newsWithScores'
        ));
    }

    // ========================
    // NEWS CRUD
    // ========================

    /**
     * List all news articles (admin)
     */
    public function newsIndex()
    {
        $news = News::latest()->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show create news form
     */
    public function newsCreate()
    {
        return view('admin.news.create');
    }

    /**
     * Store a new news article
     */
    public function newsStore(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'category' => 'required|string|max:100',
            'language' => 'required|string|max:50',
            'source'   => 'required|string|max:255',
            'image'    => 'nullable|url',
        ]);

        News::create($request->only('title', 'content', 'category', 'language', 'source', 'image'));

        return redirect()->route('admin.news.index')->with('success', 'News article created successfully!');
    }

    /**
     * Show edit news form
     */
    public function newsEdit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update a news article
     */
    public function newsUpdate(Request $request, $id)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'content'  => 'required|string',
            'category' => 'required|string|max:100',
            'language' => 'required|string|max:50',
            'source'   => 'required|string|max:255',
            'image'    => 'nullable|url',
        ]);

        $news = News::findOrFail($id);
        $news->update($request->only('title', 'content', 'category', 'language', 'source', 'image'));

        return redirect()->route('admin.news.index')->with('success', 'News article updated successfully!');
    }

    /**
     * Delete a news article
     */
    public function newsDestroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News article deleted successfully!');
    }

    // ========================
    // FEEDBACK MANAGEMENT
    // ========================

    /**
     * List all feedback entries (admin)
     */
    public function feedbackIndex()
    {
        $feedbacks = Feedback::with(['user', 'news'])->latest()->paginate(20);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    /**
     * Delete a feedback entry
     */
    public function feedbackDestroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted successfully!');
    }

    // ========================
    // ANALYTICS
    // ========================

    /**
     * Analytics page with Chart.js charts
     */
    public function analytics()
    {
        // Sentiment distribution
        $sentiments = [
            'Positive' => Feedback::where('sentiment', 'Positive')->count(),
            'Neutral'  => Feedback::where('sentiment', 'Neutral')->count(),
            'Negative' => Feedback::where('sentiment', 'Negative')->count(),
        ];

        // Bias distribution
        $biasLevels = [
            'Low'    => Feedback::where('bias_level', 'Low')->count(),
            'Medium' => Feedback::where('bias_level', 'Medium')->count(),
            'High'   => Feedback::where('bias_level', 'High')->count(),
        ];

        // Trust and clarity scores per news
        $newsWithScores = News::withAvg('feedbacks', 'trust_score')
            ->withAvg('feedbacks', 'clarity_score')
            ->withCount('feedbacks')
            ->whereHas('feedbacks')
            ->orderByDesc('feedbacks_count')
            ->take(10)
            ->get();

        // Category-wise feedback count
        $categoryFeedback = News::withCount('feedbacks')
            ->get()
            ->groupBy('category')
            ->map(function ($items) {
                return $items->sum('feedbacks_count');
            });

        return view('admin.analytics', compact('sentiments', 'biasLevels', 'newsWithScores', 'categoryFeedback'));
    }
}
