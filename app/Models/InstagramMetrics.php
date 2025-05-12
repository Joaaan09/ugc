<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramMetrics extends Model
{
    /** @use HasFactory<\Database\Factories\InstagramMetricsFactory> */
    use HasFactory;
    protected $fillable = [
        'social_profile_id', 'date', 'followers', 'likes',
        'comments', 'stories_views', 'reels_views', 'engagement_rate',
    ];

    public function socialProfile()
    {
        return $this->belongsTo(SocialProfile::class);
    }
}
