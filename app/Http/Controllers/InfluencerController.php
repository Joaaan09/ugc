<?php

namespace App\Http\Controllers;

use App\Models\Influencer;
use App\Models\Platform;
use App\Models\SocialProfile;
use App\Http\Requests\StoreInfluencerRequest;
use App\Http\Requests\UpdateInfluencerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class InfluencerController extends Controller
{
    public function showTwitchMetrics($id)
    {
        // Cargamos el influencer
        $influencer = Influencer::findOrFail($id);

        // Inicializamos $twitchProfile como null para evitar el error
        $twitchProfile = null;

        // Supongamos que tienes el nombre de usuario de Twitch
        $twitchUsername = $influencer->twitch_username;

        // Autenticación a la API de Twitch
        $clientId = config('services.twitch.client_id');
        $accessToken = config('services.twitch.access_token');

        $response = Http::withHeaders([
            'Client-ID' => $clientId,
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get('https://api.twitch.tv/helix/users', [
                    'login' => $twitchUsername,
                ]);

        $userData = $response->json()['data'][0] ?? null;

        if ($userData) {
            $twitchProfile = (object) [
                'username' => $userData['login'],
                'followers' => $userData['followers'],
                'profile_image_url' => $userData['profile_image_url'],
                'description' => $userData['description'] ?? 'No description available',
            ];
        }

        // Ahora que $twitchProfile está definido, pasamos todo a la vista
        return view('influencer.twitch', compact('influencer', 'twitchProfile'));
    }

    public function twitch($id)
{
    // Cargamos el influencer con sus redes sociales y plataformas
    $influencer = Influencer::with('socialProfiles.platform')->findOrFail($id);

    // Inicializamos $twitchProfile como null para evitar el error
    $twitchProfile = null;

    // Buscamos el perfil que tenga la plataforma Twitch
    foreach ($influencer->socialProfiles as $profile) {
        if (strtolower($profile->platform->name) === 'twich') {
            $twitchProfile = $profile;
            break;
        }
    }

    // Verifica si se encontró el perfil de Twitch
    if (!$twitchProfile) {
        return view('influencer.twitch', [
            'influencer' => $influencer,
            'twitchProfile' => null,
            'message' => "No Twitch profile found for this influencer."
        ]);
    }

    // Obtener el ID del streamer (lo usamos para varias métricas)
    $bearerToken = "Bearer nnp1e6f7iqw8uph8k5arc2w9v3yl1h"; // Token de autenticación
    $clientId = "5nu7guqjmn8dt9dkrsqr1vtbfhug7o"; // Client ID de Twitch
    $broadcasterId = $this->obtenerIdStreamer($bearerToken, $clientId, $twitchProfile->username);

    // Obtener las métricas adicionales
    $totalVistas = $this->obtenerTotalVistas($bearerToken, $clientId, $broadcasterId);
    $estadoCanal = $this->obtenerEstadoCanal($bearerToken, $clientId, $broadcasterId);
    $detallesCanal = $this->obtenerDetallesCanal($bearerToken, $clientId, $broadcasterId);

    // Pasar las métricas adicionales a la vista
    return view('influencer.twitch', compact('influencer', 'twitchProfile', 'totalVistas', 'estadoCanal', 'detallesCanal'));
}


    // Función para obtener las vistas totales del canal de Twitch
    private function obtenerTotalVistas($bearerToken, $clientId, $broadcasterId)
    {
        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
            'Client-Id' => $clientId,
        ])->get("https://api.twitch.tv/helix/videos", [
                    'user_id' => $broadcasterId,
                    'first' => 1 // Solo trae el primer video (más reciente)
                ]);

        if ($response->successful() && isset($response['data'][0]['view_count'])) {
            return $response['data'][0]['view_count'];
        }

        return null;
    }

    // Función para obtener si el canal está en vivo
    private function obtenerEstadoCanal($bearerToken, $clientId, $broadcasterId)
    {
        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
            'Client-Id' => $clientId,
        ])->get('https://api.twitch.tv/helix/streams', [
                    'user_id' => $broadcasterId
                ]);

        if ($response->successful() && !empty($response['data'])) {
            return 'En vivo: ' . $response['data'][0]['title']; // Título de la transmisión en vivo
        }

        return 'No está en vivo';
    }

    // Función para obtener más detalles sobre el canal
    private function obtenerDetallesCanal($bearerToken, $clientId, $broadcasterId)
    {
        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
            'Client-Id' => $clientId,
        ])->get('https://api.twitch.tv/helix/channels', [
                    'broadcaster_id' => $broadcasterId
                ]);

        if ($response->successful() && isset($response['data'][0])) {
            return [
                'descripcion' => $response['data'][0]['description'] ?? 'Sin descripción',
                'idioma' => $response['data'][0]['broadcaster_language'] ?? 'Desconocido'
            ];
        }

        return null;
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all influencers with their social profiles
        $influencers = Influencer::with('socialProfiles')->get();

        // Return the view with the influencers data
        return view('influencer.index', compact('influencers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all platforms for the dropdown
        $platforms = Platform::all();

        // Return the view for creating a new influencer with platforms
        return view('influencer.create', compact('platforms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInfluencerRequest $request)
    {
        try {
            DB::beginTransaction();

            // Process basic influencer data
            $influencerData = $request->validated();

            // Handle profile picture upload if present
            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('influencers', 'public');
                $influencerData['profile_picture_url'] =
                    $urlPfp = $this->obtenerFotoPerfil(
                        "Bearer nnp1e6f7iqw8uph8k5arc2w9v3yl1h",
                        "5nu7guqjmn8dt9dkrsqr1vtbfhug7o",
                        $request->name
                    );
            } else {
                $influencerData['profile_picture_url'] = $urlPfp = $this->obtenerFotoPerfil(
                    "Bearer nnp1e6f7iqw8uph8k5arc2w9v3yl1h",
                    "5nu7guqjmn8dt9dkrsqr1vtbfhug7o",
                    $request->name
                );
                ; // Set to null if no file is uploaded
            }
            // Create the influencer
            $influencer = Influencer::create($influencerData);

            // Process the first social profile
            if ($request->filled('social_username') && $request->filled('platform_id')) {
                $this->createSocialProfile(
                    $influencer->id,
                    $request->platform_id,
                    $request->social_username
                );
            }

            // Process additional social profiles
            if ($request->has('social_usernames') && $request->has('platform_ids')) {
                $usernames = $request->social_usernames;
                $platformIds = $request->platform_ids;

                foreach ($usernames as $index => $username) {
                    if (!empty($username) && !empty($platformIds[$index])) {
                        $this->createSocialProfile(
                            $influencer->id,
                            $platformIds[$index],
                            $username,

                        );
                    }
                }
            }

            DB::commit();

            // Redirect to the influencer index with a success message
            return redirect()->route('influencer.index')
                ->with('success', 'Influencer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Influencer $influencer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Influencer $influencer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInfluencerRequest $request, Influencer $influencer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Influencer $influencer)
    {
        // Eliminar el influencer directamente
        $influencer->delete();

        // Redirigir de vuelta a la lista de influencers
        return redirect()->route('influencer.index');
    }


    private function obtenerIdStreamer($bearerToken, $clientId, $nombreStreamer)
    {
        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
            'Client-Id' => $clientId,
        ])->get('https://api.twitch.tv/helix/users', [
                    'login' => $nombreStreamer
                ]);

        if ($response->successful() && isset($response['data'][0]['id'])) {
            return $response['data'][0]['id'];
        }

        return null;
    }

    private function obtenerTotalSeguidores($bearerToken, $clientId, $broadcasterId)
    {
        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
            'Client-Id' => $clientId,
        ])->get('https://api.twitch.tv/helix/channels/followers', [
                    'broadcaster_id' => $broadcasterId
                ]);

        if ($response->successful() && isset($response['total'])) {
            return $response['total'];
        }

        return null;
    }

    private function obtenerFotoPerfil($bearerToken, $clientId, $nombreStreamer)
    {
        $response = Http::withHeaders([
            'Authorization' => $bearerToken,
            'Client-Id' => $clientId,
        ])->get('https://api.twitch.tv/helix/users', [
                    'login' => $nombreStreamer
                ]);

        if ($response->successful() && isset($response['data'][0]['profile_image_url'])) {
            return $response['data'][0]['profile_image_url'];
        }

        return null;
    }


    /**
     * Create a social profile for an influencer
     */
    private function createSocialProfile($influencerId, $platformId, $username)
    {
        $platform = Platform::find($platformId);

        if (!$platform) {
            return false;
        }

        $idStreamer = $this->obtenerIdStreamer(
            "Bearer nnp1e6f7iqw8uph8k5arc2w9v3yl1h",
            "5nu7guqjmn8dt9dkrsqr1vtbfhug7o",
            $username
        );

        $stremaerFollowers = $this->obtenerTotalSeguidores(
            "Bearer nnp1e6f7iqw8uph8k5arc2w9v3yl1h",
            "5nu7guqjmn8dt9dkrsqr1vtbfhug7o",
            $idStreamer
        );



        return SocialProfile::create([
            'influencer_id' => $influencerId,
            'platform_id' => $platformId,
            'username' => $username,
            'profile_url' => $platform->base_url . $username,
            'followers_count' => $stremaerFollowers,
            'engagement_rate' => 0, // Default value
        ]);
    }

}
