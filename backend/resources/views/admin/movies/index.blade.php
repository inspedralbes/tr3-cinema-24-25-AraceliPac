@extends('layouts.admin')

@section('title', 'Gestió de Pel·lícules')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Llistat de Pel·lícules</h2>
        </div>
        <div class="col-md-6 text-end" style="margin-left: 50px;">
            <a href="{{ url('admin/movies/create') }}" style="display: inline-block; background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; font-size: 14px;">
                Nova Pel·lícula
            </a>
        </div>
    </div>

    <!-- Mensajes de éxito o error -->
    @if(session('success'))
    <div style="background-color: #d4edda; color: #155724; padding: 12px 20px; border: 1px solid #c3e6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('success') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #155724;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('error') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #721c24;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    <!-- Filtros mejorados -->
    <div style="background-color: #fff; margin-top: 50px; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Filtres</h5>
        <form action="{{ url('admin/movies') }}" method="GET">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 2; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Títol</label>
                    <input type="text" name="title" value="{{ request('title') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="flex: 2; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Gènere</label>
                    <select name="genre_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="">Tots els gèneres</option>
                        @foreach($genres ?? [] as $genre)
                        <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 100px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Any</label>
                    <input type="number" name="year" value="{{ request('year') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="flex: 1; min-width: 100px; display: flex; gap: 10px;">
                    <button type="submit" style="background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px;">Filtrar</button>
                    <a href="{{ url('admin/movies') }}" style="background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; display: inline-block; font-size: 14px;">Netejar</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de películas -->
    <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">ID</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Imatge</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Títol</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Gènere</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Director</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Any</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Duració</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies ?? [] as $movie)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $movie->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            @if($movie->image)
                            <img src="{{ $movie->image }}" alt="{{ $movie->title }}" style="width: 50px; height: auto; display: block; margin: 0 auto;">
                            @else
                            <div style="width: 50px; height: 75px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <span style="font-size: 12px;">No img</span>
                            </div>
                            @endif
                        </td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">{{ $movie->title }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $movie->genre->name ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $movie->director->name ?? '' }} {{ $movie->director->lastname ?? '' }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $movie->release_year }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $movie->duration }} min</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            <div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content: center;">
                                <a href="{{ url('admin/movies/' . $movie->id) }}" style="background-color: #D4AF37; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Veure">
                                    Veure
                                </a>
                                <a href="{{ url('admin/movies/' . $movie->id . '/edit') }}" style="background-color: #800040; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Editar">
                                    Editar
                                </a>
                                <button type="button" style="background-color: #dc3545; color: white; padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; line-height: 1.5;" title="Eliminar"
                                    onclick="if(confirm('Estàs segur que vols eliminar aquesta pel·lícula?')) { 
                                                document.getElementById('delete-form-{{ $movie->id }}').submit(); 
                                            }">
                                    Eliminar
                                </button>
                                <form id="delete-form-{{ $movie->id }}" action="{{ url('admin/movies/' . $movie->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">No s'han trobat pel·lícules</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación mejorada -->
        @if(isset($movies) && method_exists($movies, 'links'))
        <div style="margin-top: 20px; text-align: center;">
            <div style="display: inline-flex; background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                @if($movies->onFirstPage())
                <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa; border-right: 1px solid #ddd;">&laquo; Anterior</span>
                @else
                <a href="{{ $movies->previousPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">&laquo; Anterior</a>
                @endif

                @php
                $currentPage = $movies->currentPage();
                $lastPage = $movies->lastPage();
                $range = 2; // Mostrar 2 páginas antes y después de la actual
                @endphp

                @for($i = 1; $i <= $lastPage; $i++)
                    @if($i==1 || $i==$lastPage || ($i>= $currentPage - $range && $i <= $currentPage + $range))
                        @if($i==$currentPage)
                        <span style="display: inline-block; padding: 10px 16px; background-color: #800040; color: white; border-right: 1px solid #ddd;">{{ $i }}</span>
                        @else
                        <a href="{{ $movies->url($i) }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">{{ $i }}</a>
                        @endif
                        @elseif($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1)
                        <span style="display: inline-block; padding: 10px 16px; border-right: 1px solid #ddd;">...</span>
                        @endif
                        @endfor

                        @if($currentPage == $lastPage)
                        <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa;">Següent &raquo;</span>
                        @else
                        <a href="{{ $movies->nextPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none;">Següent &raquo;</a>
                        @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection