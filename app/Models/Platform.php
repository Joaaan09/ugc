<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    /** @use HasFactory<\Database\Factories\PlatformFactory> */
    use HasFactory;
    protected $fillable = ['name', 'description', 'base_url'];

    public function socialProfiles()
    {
        return $this->hasMany(SocialProfile::class);
    }


}
