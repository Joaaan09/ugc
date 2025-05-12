<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Platform;
use App\Models\SocialProfile;
use App\Models\InstagramMetric;
use App\Models\InstagramMetrics;
use App\Models\InstagramReport;
use App\Models\InstagramReports;
use Carbon\Carbon;
use DB;

class GenerateInstagramReports extends Command
{
    protected $signature = 'instagram:generate-reports';
    protected $description = 'Genera reportes mensuales con indicadores clave para perfiles de Instagram';

    public function handle()
    {
        $this->info("Generando reportes mensuales de Instagram...");

        $platform = Platform::where('name', 'Instagram')->first();
        if (!$platform) {
            $this->error('No se encontró la plataforma Instagram.');
            return;
        }

        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $profiles = SocialProfile::where('platform_id', $platform->id)->get();

        foreach ($profiles as $profile) {
            $metrics = InstagramMetrics::where('social_profile_id', $profile->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get();

            if ($metrics->count() < 2) {
                $this->warn("No hay suficientes métricas para el perfil: {$profile->username}");
                continue;
            }

            $followersStart = $metrics->first()->followers ?? 0;
            $followersEnd = $metrics->last()->followers ?? 0;

            $growthRate = $followersStart > 0
                ? round((($followersEnd - $followersStart) / $followersStart) * 100, 2)
                : null;

            $avgEngagement = round($metrics->avg('engagement_rate'), 2);
            $avgLikes = round($metrics->avg('avg_likes'));
            $avgComments = round($metrics->avg('avg_comments'));
            $avgViews = round($metrics->avg('avg_views'));

            // Estimaciones básicas (pueden mejorar con fórmula más precisa)
            $estimatedReachMin = $followersEnd * 0.1;
            $estimatedReachMax = $followersEnd * 0.25;

            $priceOptimal = $avgEngagement > 0 ? round($avgEngagement * 5, 2) : null;
            $priceMin = $priceOptimal ? round($priceOptimal * 0.8, 2) : null;
            $priceMax = $priceOptimal ? round($priceOptimal * 1.2, 2) : null;

            $postsPerWeek = round($metrics->count() / $startDate->diffInWeeks($endDate), 2);

            InstagramReports::updateOrCreate([
                'social_profile_id' => $profile->id,
                'year' => $startDate->year,
                'month' => $startDate->month,
            ], [
                'followers_start' => $followersStart,
                'followers_end' => $followersEnd,
                'growth_rate' => $growthRate,
                'avg_engagement_rate' => $avgEngagement,
                'avg_likes' => $avgLikes,
                'avg_comments' => $avgComments,
                'avg_views' => $avgViews,
                'estimated_reach_min' => $estimatedReachMin,
                'estimated_reach_max' => $estimatedReachMax,
                'estimated_post_price_min' => $priceMin,
                'estimated_post_price_max' => $priceMax,
                'estimated_post_price_optimal' => $priceOptimal,
                'posts_per_week' => $postsPerWeek,
            ]);
            
            $this->info("✔ Reporte generado para {$profile->username}");
        }

        $this->info('✅ Reportes de Instagram generados correctamente.');
    }
}
