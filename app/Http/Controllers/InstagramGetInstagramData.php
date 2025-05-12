<?php

namespace App\Http\Controllers;

use App\Models\InstagramPost;
use App\Models\InstagramReports;
use App\Models\Platform;
use App\Models\SocialProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class InstagramGetInstagramData extends Controller
{
    //invoke
    public function __invoke(Request $request)
    {
        $forupdateInstagramAcount = SocialProfile::where('platform_id', Platform::where('name', 'Instagram')->first()->id)
            ->orderBy('last_updated', 'asc')
        //    ->where('username', 'ironpanda_fitness')
            ->first();
        //$forupdateInstagramAcount->save();
        $url = 'https://i.instagram.com/api/v1/users/web_profile_info/?username=' . $forupdateInstagramAcount->username;

        // Configurar los encabezados
        $headers = [
            "User-Agent: iphone_ua",
            "x-ig-app-id: 936619743392459"
        ];

        // Inicializar cURL
        $ch = curl_init();

        // Establecer la URL y los encabezados
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Para obtener la respuesta

        // Ejecutar la solicitud
        $response = curl_exec($ch);
        // Verificar si hubo un error
        if ($response === false) {
            return response()->json(['error' => 'Error en la solicitud cURL: ' . curl_error($ch)], 500);
        }

        // Cerrar la sesión cURL
        curl_close($ch);
        // Decodificar la respuesta JSON
        $response = json_decode($response);
        if(!isset($response->status) || $response->status != 'ok') {
            return response()->json(['error' => 'Error en la respuesta de Instagram: ' . $response->message], 500);
        }
        //Actualizar SocialProfile del Instagram
        $forupdateInstagramAcount->followers_count = $response->data->user->edge_followed_by->count;
        $forupdateInstagramAcount->last_updated = now();

        $instagramReport = InstagramReports::UpdateOrCreate([
            'social_profile_id' => $forupdateInstagramAcount->id,
            'year' => now()->year,
            'month' => now()->month,
        ]);
        if ($instagramReport->followers_start == null) {
            $instagramReport->followers_start = $forupdateInstagramAcount->followers_count;
        } else {
            $instagramReport->followers_end = $forupdateInstagramAcount->followers_count;
        }
        $instagramReport->save();
        $postsNumber = $response->data->user->edge_owner_to_timeline_media->count;
           // return $response;
        foreach ($response->data->user->edge_owner_to_timeline_media->edges as $data) {
            $publishedAt = Carbon::createFromTimestamp($data->node->taken_at_timestamp, 'UTC')
                ->setTimezone('Europe/Madrid');
            //$bulk[] = $data->node->edge_media_to_caption;
            //Obtener el caption
            $caps = data_get($data, 'node.edge_media_to_caption.edges.*.node.text', []);
            $concatenado = collect($caps)->implode(' ');   // o "\n" para saltos de línea
            $bulk[] = $concatenado;
            $hashtags = collect()
                ->tap(function () use ($concatenado, &$matches) {
                    // #\p{L}[\p{L}\p{N}_]*  → ver explicación en la respuesta anterior
                    preg_match_all('/#\p{L}[\p{L}\p{N}_]*/u', $concatenado, $matches);
                })
                ->pipe(function () use (&$matches) {
                    return collect($matches[0] ?? []);
                })
                ->map(fn($tag) => Str::lower(ltrim($tag, '#'))) // quita '#' y pasa a minúsculas
                ->unique()
                ->values();          // re-indexa (0,1,2…)
            // engagement_rate = likes + comments / followers * 100
            $engagement_rate = ($data->node->edge_liked_by->count + $data->node->edge_media_to_comment->count) / $forupdateInstagramAcount->followers_count * 100;
            $instagramPost = InstagramPost::updateOrCreate(
                ['post_id' => $data->node->id], // Buscar por el `post_id`
                [
                    'social_profile_id' => $forupdateInstagramAcount->id,
                    'shortcode' => $data->node->shortcode,
                    'media_type' => $data->node->__typename,
                    'caption' => $data->node->accessibility_caption . ' ' . $concatenado, //TODO: el caption s'ha de millorar, ara nomes agafa la part autogenerada definint la imatge
                    'published_at' => isset($publishedAt) ? $publishedAt : null,
                    'likes' => $data->node->edge_liked_by->count,
                    'comments' => $data->node->edge_media_to_comment->count,
                    'tags' => $hashtags,
                    'engagement_rate' => $engagement_rate,
                    /*
                    'views' => $data['views'],
                    'engagement_rate' => $data['engagement_rate'],
                    'image_url' => $data['image_url'],
                    'video_url' => isset($data['video_url']) ? $data['video_url'] : null,
                    'owner_username' => $data['owner_username'],
                    'is_video' => $data['is_video'],
                    'tags' => isset($data['tags']) ? $data['tags'] : null,
                    'location' => isset($data['location']) ? $data['location'] : null,
                    'is_sponsored' => $data['is_sponsored'],
                    'comments_disabled' => $data['comments_disabled'],*/
                ]
            );

            // Actualizar engegement_rate del usuario

        }
        // Actualizar engegement_rate del usuario
        $forupdateInstagramAcount->engagement_rate = DB::selectOne(
            'SELECT AVG(engagement_rate) AS avg_rate 
             FROM (SELECT engagement_rate 
                   FROM instagram_posts 
                   WHERE social_profile_id = ? 
                   ORDER BY published_at DESC 
                   LIMIT 30) AS subquery', 
            [$forupdateInstagramAcount->id]
        )->avg_rate;
        $forupdateInstagramAcount->save();
        // Devolver la respuesta como JSON
        return $bulk;
    }
}
