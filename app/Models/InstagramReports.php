<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramReports extends Model
{
    /** @use HasFactory<\Database\Factories\InstagramReportsFactory> */
    use HasFactory;
    protected $fillable = [
        'social_profile_id',
        'followers_start',
        'followers_end',
        'year',
        'month',
        'followers_count',
        'posts_count',
        'likes_count',
        'comments_count',
        'followers_growth',
        'likes_growth',
        'comments_growth',
        'engagement_rate',
        'engagement_rate_growth',
        'reach',
        'impressions',
        'website_clicks',
        'profile_visits',
    ];
}
