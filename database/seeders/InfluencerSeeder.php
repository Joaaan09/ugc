<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Influencer;
use App\Models\Platform;
use Faker\Factory as Faker;

class InfluencerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $platforms = Platform::all();
    
        for ($i = 0; $i < 5; $i++) {
            $influencer = Influencer::create([
                'name' => $faker->name,
                'bio' => $faker->sentence,
                'location' => $faker->city,
                'profile_picture_url' => ''
            ]);
    
            $usedPlatforms = $platforms->random(rand(1, 2));
    
            foreach ($usedPlatforms as $platform) {
                $influencer->socialProfiles()->create([
                    'platform_id' => $platform->id,
                    'username' => $faker->userName,
                    'profile_url' => 'https://' . strtolower($platform->name) . '.com/' . $faker->userName,
                    'followers_count' => $faker->numberBetween(1000, 100000),
                    'engagement_rate' => $faker->randomFloat(2, 1, 10),
                    'extra_data' => ['verified' => $faker->boolean]
                ]);
            }
        }
    }
}
