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
        Schema::create('instagram_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_profile_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('biography')->nullable();
            $table->string('category_name')->nullable();

            
            $table->integer('followers')->nullable();
            $table->float('growth_rate')->nullable(); // en %
            $table->float('engagement_rate')->nullable(); // en %
            
            $table->integer('avg_likes')->nullable();
            $table->integer('avg_comments')->nullable();
            $table->integer('avg_views')->nullable();
            
            $table->float('estimated_reach_min')->nullable();
            $table->float('estimated_reach_max')->nullable();
            
            $table->float('estimated_post_price_min')->nullable();
            $table->float('estimated_post_price_max')->nullable();
            $table->float('estimated_post_price_optimal')->nullable();
            
            $table->float('posts_per_week')->nullable();
        
            $table->timestamps();
            $table->unique(['social_profile_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_metrics');
    }
};
