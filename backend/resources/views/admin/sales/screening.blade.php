@extends('layouts.admin')

@section('content')
<div style="margin: 20px;">
    <h1 style="color: #800040; margin-bottom: 20px; font-size: 24px; border-bottom: 2px solid #800040; padding-bottom: 10px;">Informe de Vendes per Projecció</h1>

    <!-- Selector de proyección -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px;">
        <form action="{{ route('admin.sales.screening') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div style="flex-grow: 1; min-width: 200px;">
                <label for="screening_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Seleccionar Projecció</label>
                <select id="screening_id" name="screening_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    <option value="">-- Seleccionar projecció --</option>
                    @foreach($screenings as $screening)
                    <option value="{{ $screening->id }}" {{ $selectedScreening && $selectedScreening->id == $screening->id ? 'selected' : '' }}>
                        {{ $screening->movie->title }} - {{ \Carbon\Carbon::parse($screening->screening_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($screening->screening_time)->format('H:i') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" style="background-color: #800040; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer;">
                    Consultar
                </button>
            </div>
            <div>
                <a href="{{ route('admin.sales') }}" style="display: inline-block; background-color: #6c757d; color: white; text-decoration: none; padding: 10px 16px; border-radius: 8px;">
                    Tornar
                </a>
            </div>
        </form>
    </div>

    @if($selectedScreening)
    <!-- Resumen de la proyección -->
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; min-width: 250px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
            <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Resum de la Projecció</h2>
            <p style="margin-bottom: 15px;">
                <strong>Pel·lícula:</strong> {{ $selectedScreening->movie->title }}<br>
                <strong>Data:</strong> {{ \Carbon\Carbon::parse($selectedScreening->screening_date)->format('d/m/Y') }}<br>
                <strong>Hora:</strong> {{ \Carbon\Carbon::parse($selectedScreening->screening_time)->format('H:i') }}
            </p>

            @if($screeningStats)
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #555;">Total recaptat:</span>
                <span style="font-weight: bold; color: #800040;">{{ number_format($screeningStats->total_sales, 2) }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #555;">Nombre d'entrades:</span>
                <span style="font-weight: bold;">{{ $screeningStats->tickets_count }}</span>
            </div>
            @else
            <div style="color: #555; font-style: italic;">No hi ha vendes registrades per a aquesta projecció.</div>
            @endif
        </div>
    </div>

    <!-- Lista de entradas vendidas -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
        <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Entrades Venudes</h2>

        @if(count($tickets) > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="padding: 10px; text-align: left; color: #555;">Núm. Entrada</th>
                    <th style="padding: 10px; text-align: left; color: #555;">Client</th>
                    <th style="padding: 10px; text-align: left; color: #555;">Seient</th>
                    <th style="padding: 10px; text-align: right; color: #555;">Preu</th>
                    <th style="padding: 10px; text-align: center; color: #555;">Data de Compra</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px; color: #333;">{{ $ticket->ticket_number }}</td>
                    <td style="padding: 10px; color: #333;">{{ $ticket->user->name }} {{ $ticket->user->last_name }}</td>
                    <td style="padding: 10px; color: #333;">Fila {{ $ticket->seat->row }}, Núm. {{ $ticket->seat->number }}</td>
                    <td style="padding: 10px; text-align: right; color: #800040; font-weight: bold;">{{ number_format($ticket->price, 2) }} €</td>
                    <td style="padding: 10px; text-align: center; color: #333;">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align: center; color: #555; padding: 20px;">
            No hi ha entrades venudes per a aquesta projecció.
        </div>
        @endif
    </div>
    @else
    <!-- Mensaje cuando no se ha seleccionado proyección -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; text-align: center; color: #555;">
        <p>Selecciona una projecció per veure el seu informe de vendes.</p>
    </div>
    @endif
</div>
@endsection