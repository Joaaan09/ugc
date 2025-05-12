<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetricType extends Model
{
    /** @use HasFactory<\Database\Factories\MetricTypeFactory> */
    use HasFactory;
    protected $fillable = ['platform_id', 'name', 'unit'];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }
}
