@extends('layouts.admin')

@section('title', 'Detall de Pel·lícula')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ $movie->title }}</h2>
        </div>
        <div class="col-md-6 text-end" style="margin-left: 50px;">
            <a href="{{ route('admin.movies.edit', $movie->id) }}" style="display: inline-block; background-color: #800040; color: #FFFFFF; margin-right: 10px; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; font-size: 14px;">
                Editar Pel·lícula
            </a>
            <a href="{{ route('admin.movies.index') }}" style="display: inline-block; background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; font-size: 14px;">
                Tornar al llistat
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sección de imagen y detalles generales -->
        <div class="col-md-4">
            <div style="background-color: #fff; border: 1px solid #ddd;margin-top: 50px; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); height: 100%;">
                @if($movie->image)
                <div style="text-align: center; margin-bottom: 15px;">
                    <img src="{{ $movie->image }}" alt="{{ $movie->title }}" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px;">
                </div>
                @else
                <div style="height: 300px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 15px;">
                    <span>Imatge no disponible</span>
                </div>
                @endif

                <div style="margin-top: 20px;">
                    <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Informació General</h5>
                    <ul style="padding-left: 0; list-style: none; margin-bottom: 20px;">
                        <li style="margin-bottom: 8px;"><strong style="font-weight: bold;">Gènere:</strong> {{ $movie->genre->name ?? 'N/A' }}</li>
                        <li style="margin-bottom: 8px;"><strong style="font-weight: bold;">Director:</strong> {{ $movie->director->name ?? '' }} {{ $movie->director->lastname ?? '' }}</li>
                        <li style="margin-bottom: 8px;"><strong style="font-weight: bold;">Any d'estrena:</strong> {{ $movie->release_year ?? 'N/A' }}</li>
                        <li style="margin-bottom: 8px;"><strong style="font-weight: bold;">Duració:</strong> {{ $movie->duration ?? 'N/A' }} minuts</li>
                        <li style="margin-bottom: 8px;"><strong style="font-weight: bold;">Classificació:</strong>
                            @if(isset($movie->rating))
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=$movie->rating)
                                <span style="color: #D4AF37;">★</span>
                                @else
                                <span style="color: #ccc;">★</span>
                                @endif
                                @endfor
                                @else
                                N/A
                                @endif
                        </li>
                    </ul>

                    @if($movie->trailer)
                    <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Tràiler</h5>
                    <a href="{{ $movie->trailer }}" target="_blank" style="display: inline-block; background-color: #D4AF37; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px;">
                        Veure tràiler
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sección de descripción y actores -->
        <div class="col-md-8">
            <div style="background-color: #fff;margin-top: 50px; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
                <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Descripció</h5>
                <p style="line-height: 1.6;">{{ $movie->description ?? 'Sense descripció' }}</p>
            </div>

            <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Actors</h5>
                @if($movie->actors && $movie->actors->count() > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                    @foreach($movie->actors as $actor)
                    <div style="flex: 0 0 calc(33.333% - 15px); min-width: 200px; margin-bottom: 15px;">
                        <div style="text-align: center; border: 1px solid #ddd; border-radius: 8px; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <h6 style="margin-bottom: 8px; font-weight: bold;">{{ $actor->name }} {{ $actor->lastname }}</h6>
                            @if(property_exists($actor, 'role') && $actor->role)
                            <p style="margin: 0; font-size: 0.9em; color: #666;"><small>Com: {{ $actor->role }}</small></p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p>No hi ha actors assignats a aquesta pel·lícula.</p>
                @endif
            </div>

            <!-- Botón para eliminar -->
            <div style="text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee;">
                <button type="button" style="background-color: #dc3545; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-size: 14px;" onclick="if(confirm('Estàs segur que vols eliminar aquesta pel·lícula?')) { document.getElementById('delete-form').submit(); }">
                    Eliminar Pel·lícula
                </button>
                <form id="delete-form" action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection