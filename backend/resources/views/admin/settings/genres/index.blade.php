@extends('layouts.admin')

@section('title', 'Gestió de Gèneres')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Llistat de Gèneres</h2>
        </div>
        <div class="col-md-6 text-end" style="margin-left: 50px;">
            <a href="{{ route('admin.settings.genres.create') }}" style="display: inline-block; background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; font-size: 14px;">
                Nou Gènere
            </a>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
    <div style="background-color: #fff; border: 1px solid #ddd; margin-top: 50px; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Filtres</h5>
        <form action="{{ route('admin.settings.genres.index') }}" method="GET">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 2; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Cercar</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nom del gènere" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="flex: 1; min-width: 100px; display: flex; gap: 10px;">
                    <button type="submit" style="background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px;">Cercar</button>
                    <a href="{{ route('admin.settings.genres.index') }}" style="background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; display: inline-block; font-size: 14px;">Reiniciar</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de géneros -->
    <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">#</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Nom</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Descripció</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($genres as $genre)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $genre->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left; font-weight: bold;">{{ $genre->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">
                            {{ \Illuminate\Support\Str::limit($genre->description, 100) ?: 'Sense descripció' }}
                        </td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            <div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content: center;">
                                <a href="{{ route('admin.settings.genres.show', $genre->id) }}" style="background-color: #D4AF37; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Veure">
                                    Veure
                                </a>
                                <a href="{{ route('admin.settings.genres.edit', $genre->id) }}" style="background-color: #800040; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Editar">
                                    Editar
                                </a>
                                <button type="button" style="background-color: #dc3545; color: white; padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; line-height: 1.5;" title="Eliminar"
                                    onclick="if(confirm('Estàs segur que vols eliminar aquest gènere?')) { 
                                                document.getElementById('delete-form-{{ $genre->id }}').submit(); 
                                            }">
                                    Eliminar
                                </button>
                                <form id="delete-form-{{ $genre->id }}" action="{{ route('admin.settings.genres.destroy', $genre->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">No s'han trobat gèneres</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación mejorada -->
        @if(isset($genres) && method_exists($genres, 'links'))
        <div style="margin-top: 20px; text-align: center;">
            <div style="display: inline-flex; background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                @if($genres->onFirstPage())
                <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa; border-right: 1px solid #ddd;">&laquo; Anterior</span>
                @else
                <a href="{{ $genres->previousPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">&laquo; Anterior</a>
                @endif

                @php
                $currentPage = $genres->currentPage();
                $lastPage = $genres->lastPage();
                $range = 2; // Mostrar 2 páginas antes y después de la actual
                @endphp

                @for($i = 1; $i <= $lastPage; $i++)
                    @if($i==1 || $i==$lastPage || ($i>= $currentPage - $range && $i <= $currentPage + $range))
                        @if($i==$currentPage)
                        <span style="display: inline-block; padding: 10px 16px; background-color: #800040; color: white; border-right: 1px solid #ddd;">{{ $i }}</span>
                        @else
                        <a href="{{ $genres->url($i) }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">{{ $i }}</a>
                        @endif
                        @elseif($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1)
                        <span style="display: inline-block; padding: 10px 16px; border-right: 1px solid #ddd;">...</span>
                        @endif
                        @endfor

                        @if($currentPage == $lastPage)
                        <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa;">Següent &raquo;</span>
                        @else
                        <a href="{{ $genres->nextPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none;">Següent &raquo;</a>
                        @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Enlace para regresar -->
    <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('admin.settings') }}" style="display: inline-block; background-color: #6c757d; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px;">
            Tornar a Configuració
        </a>
    </div>
</div>
@endsection