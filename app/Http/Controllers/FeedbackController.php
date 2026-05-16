<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\News;
use App\Mail\FeedbackConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    /**
     * Store a new feedback entry
     */
    public function store(Request $request)
    {
        $request->validate([
            'news_id'       => 'required|exists:news,id',
            'trust_score'   => 'required|integer|min:1|max:5',
            'clarity_score' => 'required|integer|min:1|max:5',
            'bias_level'    => 'required|in:Low,Medium,High',
            'sentiment'     => 'required|in:Positive,Neutral,Negative',
            'comment'       => 'required|string|min:10',
        ]);

        $feedback = Feedback::create([
            'user_id'       => Auth::id(),
            'news_id'       => $request->news_id,
            'trust_score'   => $request->trust_score,
            'clarity_score' => $request->clarity_score,
            'bias_level'    => $request->bias_level,
            'sentiment'     => $request->sentiment,
            'comment'       => $request->comment,
        ]);

        // Send confirmation email
        try {
            Mail::to(Auth::user()->email)->send(new FeedbackConfirmation($feedback));
        } catch (\Exception $e) {
            // Log the error but don't block the user
            \Log::error('Email sending failed: ' . $e->getMessage());
        }

        return redirect()->route('news.show', $request->news_id)
            ->with('success', 'Your feedback has been submitted successfully!');
    }

    /**
     * User dashboard - show user's submitted feedback
     */
    public function userDashboard()
    {
        $feedbacks = Feedback::with('news')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.dashboard', compact('feedbacks'));
    }
}
