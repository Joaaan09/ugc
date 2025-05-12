<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    /** @use HasFactory<\Database\Factories\InfluencerFactory> */
    use HasFactory;
    protected $fillable = ['name', 'bio', 'location', 'profile_picture_url'];

    public function socialProfiles()
    {
        return $this->hasMany(SocialProfile::class);
    }
}
