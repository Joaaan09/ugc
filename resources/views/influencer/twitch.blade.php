@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">{{ $influencer->name }}'s Twitch Metrics</h1>

        @if ($twitchProfile)
            <div class="row">
                <!-- Perfil de Twitch -->
                <div class="col-md-4">
                    <div class="card">
                <img src="{{ $influencer->profile_picture_url }}" alt="{{ $twitchProfile->username }}'s Profile Picture">
                        <div class="card-body">
                            <h5 class="card-title">{{ $twitchProfile->username }}</h5>
                            <p><strong>Followers:</strong> {{ $twitchProfile->followers_count }}</p>
                            <p><strong>Profile URL:</strong> <a href="{{ $twitchProfile->profile_url }}" target="_blank">{{ $twitchProfile->profile_url }}</a></p>
                        </div>
                    </div>
                </div>

                <!-- Métricas adicionales -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Additional Metrics</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Total Views:</strong> {{ $totalVistas ?? 'N/A' }}</p>
                            <p><strong>Channel Status:</strong> {{ $estadoCanal }}</p>
                            <p><strong>Description:</strong> {{ $detallesCanal['descripcion'] ?? 'No description available' }}</p>
                            <p><strong>Language:</strong> {{ $detallesCanal['idioma'] ?? 'Unknown' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <p class="alert alert-warning">No Twitch profile found for this influencer.</p>
        @endif
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-img-top {
            border-radius: 8px 8px 0 0;
            object-fit: cover;
            height: 200px;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.5rem;
            color: #333;
        }

        .container {
            max-width: 1200px;
        }

        h1 {
            font-size: 2.5rem;
            color: #007bff;
        }

        .alert-warning {
            margin-top: 20px;
            font-size: 1.2rem;
            color: #856404;
            background-color: #fff3cd;
            border-color: #ffeeba;
        }

        /* Estilos adicionales */
        .row {
            margin-top: 30px;
        }

        a {
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
@endsection
