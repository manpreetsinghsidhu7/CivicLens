<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page with latest news
     */
    public function index()
    {
        $latestNews = News::latest()->take(6)->get();
        $categories = News::distinct()->pluck('category');

        return view('home', compact('latestNews', 'categories'));
    }
}
