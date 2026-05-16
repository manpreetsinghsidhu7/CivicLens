<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\News;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * GET /api/news - List all news articles
     */
    public function newsIndex()
    {
        $news = News::latest()->paginate(20);
        return response()->json([
            'success' => true,
            'data'    => $news,
        ]);
    }

    /**
     * GET /api/news/{id} - Show a single news article
     */
    public function newsShow($id)
    {
        $news = News::with('feedbacks')->find($id);

        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $news,
        ]);
    }

    /**
     * POST /api/feedback - Store feedback via API
     */
    public function feedbackStore(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'news_id'       => 'required|exists:news,id',
            'trust_score'   => 'required|integer|min:1|max:5',
            'clarity_score' => 'required|integer|min:1|max:5',
            'bias_level'    => 'required|in:Low,Medium,High',
            'sentiment'     => 'required|in:Positive,Neutral,Negative',
            'comment'       => 'required|string|min:10',
        ]);

        $feedback = Feedback::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Feedback submitted successfully.',
            'data'    => $feedback,
        ], 201);
    }

    /**
     * GET /api/feedback - List all feedback
     */
    public function feedbackIndex()
    {
        $feedback = Feedback::with(['user', 'news'])->latest()->paginate(20);
        return response()->json([
            'success' => true,
            'data'    => $feedback,
        ]);
    }
}
