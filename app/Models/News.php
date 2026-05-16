<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title', 'content', 'category', 'language', 'source',
        'image', 'article_id', 'source_url', 'source_type', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static $languageMap = [
        'en' => 'English', 'hi' => 'Hindi', 'ta' => 'Tamil', 'te' => 'Telugu',
        'kn' => 'Kannada', 'ml' => 'Malayalam', 'bn' => 'Bengali', 'mr' => 'Marathi',
        'gu' => 'Gujarati', 'pa' => 'Punjabi', 'ur' => 'Urdu', 'or' => 'Odia',
    ];

    public static $categories = [
        'politics', 'business', 'technology', 'science', 'health', 'sports',
        'entertainment', 'education', 'environment', 'food', 'tourism',
        'world', 'top', 'domestic', 'crime', 'lifestyle', 'other',
    ];

    public function feedbacks() { return $this->hasMany(Feedback::class); }
    public function averageTrustScore() { return $this->feedbacks()->avg('trust_score'); }
    public function averageClarityScore() { return $this->feedbacks()->avg('clarity_score'); }

    public static function languageName(string $code): string
    {
        return self::$languageMap[$code] ?? ucfirst($code);
    }

    /**
     * Scope: order by published date (fallback to created_at)
     */
    public function scopeLatestPublished($query)
    {
        return $query->orderByRaw('COALESCE(published_at, created_at) DESC');
    }

    public function scopeOldestPublished($query)
    {
        return $query->orderByRaw('COALESCE(published_at, created_at) ASC');
    }
}
