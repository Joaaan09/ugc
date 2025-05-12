<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialProfile extends Model
{
    /** @use HasFactory<\Database\Factories\SocialProfileFactory> */
    use HasFactory;
    protected $fillable = [
        'influencer_id',
        'platform_id',
        'username',
        'profile_url',
        'profile_picture',
        'followers_count',
        'engagement_rate',
        'extra_data',
        'last_updated',
    ];

    protected $casts = [
        'extra_data' => 'array'
    ];

    public function influencer(): BelongsTo
    {
        return $this->belongsTo(Influencer::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(Metric::class);
    }


    public function instagramMetrics(): HasMany
    {
        return $this->hasMany(InstagramMetrics::class);
    }


    public function youtubeMetrics()
    {
        return $this->hasMany(YoutubeMetrics::class);
    }

    public function tiktokMetrics()
    {
        return $this->hasMany(TiktokMetrics::class);
    }
    /**
     * Get the Instagram posts associated with the social profile.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instagramPosts(): HasMany
    {
        return $this->hasMany(InstagramPost::class)
                    ->whereHas('platform', fn ($q) => $q->where('name', 'Instagram'));
    }
}
