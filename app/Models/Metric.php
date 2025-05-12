<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    /** @use HasFactory<\Database\Factories\MetricFactory> */
    use HasFactory;
    protected $fillable = ['social_profile_id', 'metric_type_id', 'date', 'value'];

    protected $dates = ['date'];

    public function socialProfile()
    {
        return $this->belongsTo(SocialProfile::class);
    }

    public function metricType()
    {
        return $this->belongsTo(MetricType::class);
    }
}
