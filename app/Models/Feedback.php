<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'feedback';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'news_id',
        'trust_score',
        'clarity_score',
        'bias_level',
        'sentiment',
        'comment',
    ];

    /**
     * Feedback belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Feedback belongs to a news article
     */
    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
