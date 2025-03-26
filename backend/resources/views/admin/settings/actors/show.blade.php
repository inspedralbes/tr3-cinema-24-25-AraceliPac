@extends('layouts.admin')

@section('title', 'Detalls d\'Actor')

@section('content')
<div style="margin: 20px;">
    <div style="margin-bottom: 20px;">
        <h1 style="color: #800040; font-size: 24px; margin-bottom: 5px;">Detalls de l'Actor</h1>
        <p style="color: #555; margin: 0;">Informació completa sobre l'actor {{ $actor->name }} {{ $actor->lastname }}.</p>
    </div>

    <!-- Tarjeta de detalles -->
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
        <!-- Imagen del actor (si existe) -->
        <div style="flex: 0 0 250px;">
            @if($actor->image)
            <img src="{{ $actor->image }}" alt="{{ $actor->name }}" style="width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            @else
            <div style="width: 100%; height: 300px; background-color: #f1f1f1; border-radius: 8px; display: flex; justify-content: center; align-items: center; color: #666;">
                <span>Sense imatge</span>
            </div>
            @endif
        </div>

        <!-- Detalles del actor -->
        <div style="flex: 1; min-width: 300px;">
            <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
                <h2 style="color: #800040; font-size: 22px; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    {{ $actor->name }} {{ $actor->lastname }}
                </h2>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">ID:</p>
                    <p style="margin: 0; color: #333;">{{ $actor->id }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">Nom:</p>
                    <p style="margin: 0; color: #333;">{{ $actor->name }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">Cognom:</p>
                    <p style="margin: 0; color: #333;">{{ $actor->lastname ?: 'No especificat' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">Data de Naixement:</p>
                    <p style="margin: 0; color: #333;">
                        {{ $actor->birth_date ? \Carbon\Carbon::parse($actor->birth_date)->format('d/m/Y') : 'No especificada' }}
                    </p>
                </div>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">Nacionalitat:</p>
                    <p style="margin: 0; color: #333;">{{ $actor->nationality ?: 'No especificada' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">Biografia:</p>
                    <p style="margin: 0; color: #333; white-space: pre-line;">{{ $actor->bio ?: 'No disponible' }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">Creat el:</p>
                    <p style="margin: 0; color: #333;">{{ $actor->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <div style="margin-bottom: 15px;">
                    <p style="margin: 0 0 5px; color: #555; font-weight: bold;">Actualitzat el:</p>
                    <p style="margin: 0; color: #333;">{{ $actor->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div style="display: flex; gap: 10px; margin-bottom: 30px;">
        <a href="{{ route('admin.settings.actors.edit', $actor->id) }}" style="background-color: #28a745; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px; display: inline-block;">
            Editar Actor
        </a>
        <form action="{{ route('admin.settings.actors.destroy', $actor->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Estàs segur que vols eliminar aquest actor?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background-color: #dc3545; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                Eliminar Actor
            </button>
        </form>
        <a href="{{ route('admin.settings.actors.index') }}" style="background-color: #6c757d; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px; display: inline-block;">
            Tornar al Llistat
        </a>
    </div>

    <!-- Aquí  mostrar películas relacionadas -->
    <!--
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px;">
        <h3 style="color: #800040; font-size: 18px; margin-top: 0; margin-bottom: 15px;">Pel·lícules en què Participa</h3>
        
        @if($actor->movies && $actor->movies->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
            @foreach($actor->movies as $movie)
            <div style="background-color: #f8f9fa; border-radius: 5px; padding: 10px;">
                <p style="font-weight: bold; margin: 0 0 5px;">{{ $movie->title }}</p>
                <p style="margin: 0; color: #666;">{{ $movie->release_year }}</p>
            </div>
            @endforeach
        </div>
        @else
        <p style="color: #666; font-style: italic;">Aquest actor no apareix a cap pel·lícula registrada.</p>
        @endif
    </div>
    -->
</div>
@endsection