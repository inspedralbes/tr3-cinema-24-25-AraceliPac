@extends('layouts.admin')

@section('title', 'Detall de Pel·lícula')

@section('content')
<style>
    .dashboard-card {
        padding: 20px;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-custom {
        background-color: #800040;
        color: #fff;
        border-radius: 8px;
        padding: 10px 16px;
    }

    .btn-custom:hover {
        background-color: #a00050;
        color: #fff;
    }

    .movie-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }

    .info-section h5 {
        color: #800040;
        margin-bottom: 10px;
    }

    .info-section ul {
        padding-left: 0;
        list-style: none;
    }

    .actor-card {
        text-align: center;
        border: none;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 15px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ $movie->title }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-custom">
                Editar Pel·lícula
            </a>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">
                Tornar al llistat
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sección de imagen y detalles generales -->
        <div class="col-md-4">
            <div class="dashboard-card h-100 text-center">
                @if($movie->image)
                <img src="{{ $movie->image }}" class="movie-image mb-3" alt="{{ $movie->title }}">
                @else
                <div style="height: 300px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                    <span>Imatge no disponible</span>
                </div>
                @endif

                <div class="info-section mt-3">
                    <h5>Informació General</h5>
                    <ul>
                        <li><strong>Gènere:</strong> {{ $movie->genre->name ?? 'N/A' }}</li>
                        <li><strong>Director:</strong> {{ $movie->director->name ?? '' }} {{ $movie->director->lastname ?? '' }}</li>
                        <li><strong>Any d'estrena:</strong> {{ $movie->release_year ?? 'N/A' }}</li>
                        <li><strong>Duració:</strong> {{ $movie->duration ?? 'N/A' }} minuts</li>
                        <li><strong>Classificació:</strong> {{ $movie->rating ?? 'N/A' }}</li>
                    </ul>

                    @if($movie->trailer)
                    <h5>Tràiler</h5>
                    <a href="{{ $movie->trailer }}" target="_blank" class="btn btn-sm" style="background-color: #D4AF37; color: #FFFFFF;">
                        Veure tràiler
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sección de descripción y actores -->
        <div class="col-md-8">
            <div class="dashboard-card mb-4">
                <h5>Descripció</h5>
                <p>{{ $movie->description ?? 'Sense descripció' }}</p>
            </div>

            <div class="dashboard-card">
                <h5>Actors</h5>
                @if($movie->actors && $movie->actors->count() > 0)
                <div class="row">
                    @foreach($movie->actors as $actor)
                    <div class="col-md-4 mb-3">
                        <div class="card actor-card">
                            <div class="card-body">
                                <h6 class="card-title">{{ $actor->name }} {{ $actor->lastname }}</h6>
                                @if(property_exists($actor, 'role') && $actor->role)
                                <p class="card-text"><small>Com: {{ $actor->role }}</small></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p>No hi ha actors assignats a aquesta pel·lícula.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection