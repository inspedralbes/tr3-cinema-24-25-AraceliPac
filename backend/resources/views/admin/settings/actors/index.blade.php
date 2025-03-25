@extends('layouts.admin')

@section('title', 'Gestió d\'Actors')

@section('content')
<div style="margin: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="color: #800040; font-size: 24px; margin: 0;">Llistat d'Actors</h1>
        <a href="{{ route('admin.settings.actors.create') }}" style="background-color: #800040; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px; display: inline-block;">
            <span style="margin-right: 5px;">+</span> Nou Actor
        </a>
    </div>

    <!-- Filtros de búsqueda -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px;">
        <form action="{{ route('admin.settings.actors.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div style="flex-grow: 1; min-width: 200px;">
                <label for="search" style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Cercar</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nom, cognom o nacionalitat" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
            </div>
            <div>
                <button type="submit" style="background-color: #800040; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                    Cercar
                </button>
            </div>
            <div>
                <a href="{{ route('admin.settings.actors.index') }}" style="display: inline-block; background-color: #6c757d; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px;">
                    Reiniciar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de actores -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="padding: 12px 15px; text-align: left; color: #555;">#</th>
                    <th style="padding: 12px 15px; text-align: left; color: #555;">Nom</th>
                    <th style="padding: 12px 15px; text-align: left; color: #555;">Cognom</th>
                    <th style="padding: 12px 15px; text-align: left; color: #555;">Nacionalitat</th>
                    <th style="padding: 12px 15px; text-align: left; color: #555;">Data de Naixement</th>
                    <th style="padding: 12px 15px; text-align: center; color: #555;">Accions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($actors as $actor)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 15px; color: #333;">{{ $actor->id }}</td>
                    <td style="padding: 12px 15px; color: #333;">{{ $actor->name }}</td>
                    <td style="padding: 12px 15px; color: #333;">{{ $actor->lastname ?? '-' }}</td>
                    <td style="padding: 12px 15px; color: #333;">{{ $actor->nationality ?? '-' }}</td>
                    <td style="padding: 12px 15px; color: #333;">
                        {{ $actor->birth_date ? \Carbon\Carbon::parse($actor->birth_date)->format('d/m/Y') : '-' }}
                    </td>
                    <td style="padding: 12px 15px; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 8px;">
                            <a href="{{ route('admin.settings.actors.show', $actor->id) }}" style="color: #17a2b8; text-decoration: none; padding: 6px 10px; border: 1px solid #17a2b8; border-radius: 4px; font-size: 14px;" title="Veure">
                                👁️
                            </a>
                            <a href="{{ route('admin.settings.actors.edit', $actor->id) }}" style="color: #28a745; text-decoration: none; padding: 6px 10px; border: 1px solid #28a745; border-radius: 4px; font-size: 14px;" title="Editar">
                                ✏️
                            </a>
                            <form action="{{ route('admin.settings.actors.destroy', $actor->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Estàs segur que vols eliminar aquest actor?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #dc3545; background: none; border: 1px solid #dc3545; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 14px;" title="Eliminar">
                                    🗑️
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 20px; text-align: center; color: #777;">No s'han trobat actors</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div style="display: flex; justify-content: center; margin: 20px 0;">
        {{ $actors->links() }}
    </div>

    <!-- Enlace para regresar -->
    <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('admin.settings') }}" style="display: inline-block; background-color: #6c757d; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px;">
            Tornar a Configuració
        </a>
    </div>
</div>
@endsection