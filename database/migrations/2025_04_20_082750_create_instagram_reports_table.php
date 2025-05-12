<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instagram_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_profile_id')->constrained()->onDelete('cascade');
            
            $table->unsignedInteger('year');
            $table->unsignedTinyInteger('month'); // 1–12
            
            // Indicadores principales
            $table->integer('followers_start')->nullable();   // seguidores al inicio del mes
            $table->integer('followers_end')->nullable();     // seguidores al final
            $table->float('growth_rate')->nullable();         // en %
            
            $table->float('avg_engagement_rate')->nullable(); // promedio del mes
            $table->integer('avg_likes')->nullable();         // promedio por post
            $table->integer('avg_comments')->nullable();
            $table->integer('avg_views')->nullable();
            
            $table->float('estimated_reach_min')->nullable();
            $table->float('estimated_reach_max')->nullable();
            
            $table->float('estimated_post_price_min')->nullable();
            $table->float('estimated_post_price_max')->nullable();
            $table->float('estimated_post_price_optimal')->nullable();
            
            $table->float('posts_per_week')->nullable();
            $table->timestamps();
        
            $table->unique(['social_profile_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_reports');
    }
};
