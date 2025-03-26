@extends('layouts.admin')

@section('title', 'Gestió de Projeccions')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Llistat de Projeccions</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/screenings/create') }}" style="display: inline-block; background-color: #800040; color: #FFFFFF; padding: 10px 16px; margin-left: 50px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; font-size: 14px;">
                Nova Projecció
            </a>
        </div>
    </div>

    <!-- Mensajes de éxito o error -->
    @if(session('success'))
    <div style="background-color: #d4edda;margin-top: 50px;  color: #155724; padding: 12px 20px; border: 1px solid #c3e6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('success') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #155724;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div style="background-color: #f8d7da; margin-top: 50px; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('error') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #721c24;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    <!-- Filtros mejorados -->
    <div style="background-color: #fff;margin-top: 50px; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Filtres</h5>
        <form action="{{ url('admin/screenings') }}" method="GET">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label for="movie_id" style="display: block; margin-bottom: 8px; font-weight: 500;">Pel·lícula</label>
                    <select id="movie_id" name="movie_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="">Totes les pel·lícules</option>
                        @foreach($movies ?? [] as $movie)
                        <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                            {{ $movie->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label for="date" style="display: block; margin-bottom: 8px; font-weight: 500;">Data</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="flex: 1; min-width: 200px; display: flex; gap: 10px;">
                    <button type="submit" style="background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px;">Filtrar</button>
                    <a href="{{ url('admin/screenings') }}" style="background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; display: inline-block; font-size: 14px;">Netejar</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de proyecciones -->
    <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">ID</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Pel·lícula</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Data</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Hora</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Dia especial</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Estat</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($screenings ?? [] as $screening)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $screening->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">{{ $screening->movie->title ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ date('d/m/Y', strtotime($screening->screening_date)) }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ date('H:i', strtotime($screening->screening_time)) }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            @if($screening->is_special_day)
                            <span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">Sí</span>
                            @else
                            <span style="background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">No</span>
                            @endif
                        </td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            @if($screening->is_full)
                            <span style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">Complet</span>
                            @else
                            <span style="background-color: #28a745; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">Disponible</span>
                            @endif
                        </td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            <div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content: center;">
                                <a href="{{ url('admin/screenings/' . $screening->id) }}" style="background-color: #D4AF37; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Veure">
                                    Veure
                                </a>
                                <a href="{{ url('admin/screenings/' . $screening->id . '/edit') }}" style="background-color: #800040; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Editar">
                                    Editar
                                </a>
                                <button type="button" style="background-color: #dc3545; color: white; padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; line-height: 1.5;" title="Eliminar"
                                    onclick="if(confirm('Estàs segur que vols eliminar aquesta projecció?')) { 
                                                document.getElementById('delete-form-{{ $screening->id }}').submit(); 
                                            }">
                                    Eliminar
                                </button>
                                <form id="delete-form-{{ $screening->id }}" action="{{ url('admin/screenings/' . $screening->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">No s'han trobat projeccions</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación mejorada -->
        @if(isset($screenings) && method_exists($screenings, 'links'))
        <div style="margin-top: 20px; text-align: center;">
            <div style="display: inline-flex; background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                @if($screenings->onFirstPage())
                <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa; border-right: 1px solid #ddd;">&laquo; Anterior</span>
                @else
                <a href="{{ $screenings->previousPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">&laquo; Anterior</a>
                @endif

                @php
                $currentPage = $screenings->currentPage();
                $lastPage = $screenings->lastPage();
                $range = 2; // Mostrar 2 páginas antes y después de la actual
                @endphp

                @for($i = 1; $i <= $lastPage; $i++)
                    @if($i==1 || $i==$lastPage || ($i>= $currentPage - $range && $i <= $currentPage + $range))
                        @if($i==$currentPage)
                        <span style="display: inline-block; padding: 10px 16px; background-color: #800040; color: white; border-right: 1px solid #ddd;">{{ $i }}</span>
                        @else
                        <a href="{{ $screenings->url($i) }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">{{ $i }}</a>
                        @endif
                        @elseif($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1)
                        <span style="display: inline-block; padding: 10px 16px; border-right: 1px solid #ddd;">...</span>
                        @endif
                        @endfor

                        @if($currentPage == $lastPage)
                        <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa;">Següent &raquo;</span>
                        @else
                        <a href="{{ $screenings->nextPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none;">Següent &raquo;</a>
                        @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection