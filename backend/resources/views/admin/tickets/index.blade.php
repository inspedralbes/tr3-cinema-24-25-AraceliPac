@extends('layouts.admin')

@section('title', 'Gestió d\'Entrades')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Llistat d'Entrades</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/tickets/create') }}" style="display: inline-block; background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; font-size: 14px;">
                Nova Entrada
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

    <!-- Filtros -->
    <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Filtres</h5>
        <form action="{{ url('admin/tickets') }}" method="GET">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Número d'entrada</label>
                    <input type="text" name="ticket_number" value="{{ request('ticket_number') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Usuari</label>
                    <select name="user_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="">Tots els usuaris</option>
                        @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Pel·lícula</label>
                    <select name="movie_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="">Totes les pel·lícules</option>
                        @foreach($movies ?? [] as $movie)
                        <option value="{{ $movie->id }}" {{ request('movie_id') == $movie->id ? 'selected' : '' }}>
                            {{ $movie->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 0.5; min-width: 100px; display: flex; gap: 10px;">
                    <button type="submit" style="background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px;">Filtrar</button>
                    <a href="{{ url('admin/tickets') }}" style="background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; display: inline-block; font-size: 14px;">Netejar</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabla de tickets -->
    <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">ID</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Número</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Pel·lícula</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Data i Hora</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Usuari</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Seient</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Preu</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets ?? [] as $ticket)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $ticket->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $ticket->ticket_number }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">{{ $ticket->screening->movie->title ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            @if(isset($ticket->screening))
                            {{ date('d/m/Y', strtotime($ticket->screening->screening_date)) }} {{ date('H:i', strtotime($ticket->screening->screening_time)) }}
                            @else
                            N/A
                            @endif
                        </td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $ticket->user->name ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $ticket->seat->seat_number ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ number_format($ticket->price, 2) }} €</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            <div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content: center;">
                                <a href="{{ url('admin/tickets/' . $ticket->id) }}" style="background-color: #D4AF37; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Veure">
                                    Veure
                                </a>
                                <a href="{{ url('admin/tickets/' . $ticket->id . '/edit') }}" style="background-color: #800040; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Editar">
                                    Editar
                                </a>
                                <button type="button" style="background-color: #dc3545; color: white; padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; line-height: 1.5;" title="Eliminar"
                                    onclick="if(confirm('Estàs segur que vols eliminar aquesta entrada?')) { 
                                                document.getElementById('delete-form-{{ $ticket->id }}').submit(); 
                                            }">
                                    Eliminar
                                </button>
                                <form id="delete-form-{{ $ticket->id }}" action="{{ url('admin/tickets/' . $ticket->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">No s'han trobat entrades</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if(isset($tickets) && method_exists($tickets, 'links'))
        <div style="margin-top: 20px; text-align: center;">
            <div style="display: inline-flex; background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                @if($tickets->onFirstPage())
                <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa; border-right: 1px solid #ddd;">&laquo; Anterior</span>
                @else
                <a href="{{ $tickets->previousPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">&laquo; Anterior</a>
                @endif

                @php
                $currentPage = $tickets->currentPage();
                $lastPage = $tickets->lastPage();
                $range = 2; // Mostrar 2 páginas antes y después de la actual
                @endphp

                @for($i = 1; $i <= $lastPage; $i++)
                    @if($i==1 || $i==$lastPage || ($i>= $currentPage - $range && $i <= $currentPage + $range))
                        @if($i==$currentPage)
                        <span style="display: inline-block; padding: 10px 16px; background-color: #800040; color: white; border-right: 1px solid #ddd;">{{ $i }}</span>
                        @else
                        <a href="{{ $tickets->url($i) }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">{{ $i }}</a>
                        @endif
                        @elseif($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1)
                        <span style="display: inline-block; padding: 10px 16px; border-right: 1px solid #ddd;">...</span>
                        @endif
                        @endfor

                        @if($currentPage == $lastPage)
                        <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa;">Següent &raquo;</span>
                        @else
                        <a href="{{ $tickets->nextPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none;">Següent &raquo;</a>
                        @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection