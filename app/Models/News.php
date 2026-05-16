<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'content',
        'category',
        'language',
        'source',
        'image',
    ];

    /**
     * A news article has many feedback entries
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Get average trust score
     */
    public function averageTrustScore()
    {
        return $this->feedbacks()->avg('trust_score');
    }

    /**
     * Get average clarity score
     */
    public function averageClarityScore()
    {
        return $this->feedbacks()->avg('clarity_score');
    }
}
