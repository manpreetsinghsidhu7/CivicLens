<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Home page — shows all news with pagination (200 per page), ordered by published date
     */
    public function index()
    {
        $latestNews = News::latestPublished()->paginate(200);
        $categories = News::distinct()->pluck('category')->filter()->sort()->values();
        $totalNews = News::count();
        $totalFeedback = \App\Models\Feedback::count();
        $totalUsers = \App\Models\User::count();

        return view('home', compact('latestNews', 'categories', 'totalNews', 'totalFeedback', 'totalUsers'));
    }
}
