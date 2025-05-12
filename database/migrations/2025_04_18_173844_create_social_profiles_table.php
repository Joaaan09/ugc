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
        Schema::create('social_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('influencer_id')->constrained()->onDelete('cascade');
            $table->foreignId('platform_id')->constrained()->onDelete('cascade');
            $table->string('username');
            $table->string('profile_url')->default('')->nullable();
            $table->string('profile_picture')->default('')->nullable();
            $table->integer('followers_count')->default(0);
            $table->float('engagement_rate')->nullable();
            $table->json('extra_data')->nullable(); // datos variables
            $table->date('last_updated')->nullable(); // fecha de la última actualización
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_profiles');
    }
};
