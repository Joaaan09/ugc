<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramPost extends Model
{
    /** @use HasFactory<\Database\Factories\InstagramPostFactory> */
    use HasFactory;
    protected $fillable = [
        'social_profile_id',
        'post_id',
        'shortcode',
        'media_type',
        'caption',
        'published_at',
        'likes',
        'comments',
        'views',
        'engagement_rate',
        'image_url',
        'video_url',
        'owner_username',
        'is_video',
        'tags',
        'location',
        'is_sponsored',
        'comments_disabled',
    ];

    // Indica que la tabla no tiene un campo de "deleted_at" (si no estás usando soft deletes)
    protected $dates = ['published_at'];
    
    public function socialProfile()
{
    return $this->belongsTo(SocialProfile::class);
}
}
