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
        Schema::create('instagram_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_profile_id')->constrained()->onDelete('cascade');
            $table->string('shortcode')->nullable();
            $table->string('post_id')->unique(); // ID externo del post (API)
            $table->string('media_type')->nullable(); // image, video, reel, story
            $table->string('caption')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->text('tags')->nullable(); // Podrías almacenar esto como un JSON o un string separado por comas
        
            $table->integer('likes')->nullable();
            $table->integer('comments')->nullable();
            $table->integer('views')->nullable(); // para videos o reels
        
            $table->float('engagement_rate')->nullable(); // calculado en base a followers en el momento
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_posts');
    }
};
