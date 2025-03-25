@extends('layouts.admin')

@section('title', 'Detall d\'Entrada')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Detall d'Entrada #{{ $ticket->ticket_number }}</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/tickets/' . $ticket->id . '/edit') }}" style="display: inline-block; background-color: #800040; color: #FFFFFF; margin-right: 10px; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; font-size: 14px;">
                Editar Entrada
            </a>
            <a href="{{ url('admin/tickets') }}" style="display: inline-block; background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; font-size: 14px;">
                Tornar al llistat
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <!-- Información del ticket -->
                <div style="margin-bottom: 25px; text-align: center;">
                    <h3 style="color: #800040; margin-bottom: 15px; font-weight: bold; font-size: 24px;">Entrada Cinema</h3>
                    <div style="font-size: 16px; margin-bottom: 5px;">
                        <strong>Número:</strong> {{ $ticket->ticket_number }}
                    </div>
                    @if($ticket->qr_code)
                    <div style="margin: 15px auto; max-width: 200px;">
                        <img src="{{ $ticket->qr_code }}" alt="QR Code" style="max-width: 100%; height: auto;">
                    </div>
                    @endif
                </div>

                <!-- Información de la película y proyección -->
                <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Detalls de la Pel·lícula</h4>
                    <div style="display: flex; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 200px; padding-right: 15px;">
                            @if(isset($ticket->screening->movie->image) && $ticket->screening->movie->image)
                            <img src="{{ $ticket->screening->movie->image }}" alt="{{ $ticket->screening->movie->title ?? 'Pel·lícula' }}" style="max-width: 100%; height: auto; max-height: 200px; border-radius: 8px; display: block; margin-bottom: 15px;">
                            @else
                            <div style="height: 150px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 15px;">
                                <span>Imatge no disponible</span>
                            </div>
                            @endif
                        </div>
                        <div style="flex: 2; min-width: 200px;">
                            <p style="margin-bottom: 10px;">
                                <strong style="font-weight: bold;">Títol:</strong>
                                {{ $ticket->screening->movie->title ?? 'N/A' }}
                            </p>
                            <p style="margin-bottom: 10px;">
                                <strong style="font-weight: bold;">Data:</strong>
                                {{ isset($ticket->screening->screening_date) ? date('d/m/Y', strtotime($ticket->screening->screening_date)) : 'N/A' }}
                            </p>
                            <p style="margin-bottom: 10px;">
                                <strong style="font-weight: bold;">Hora:</strong>
                                {{ isset($ticket->screening->screening_time) ? date('H:i', strtotime($ticket->screening->screening_time)) : 'N/A' }}
                            </p>
                            <p style="margin-bottom: 10px;">
                                <strong style="font-weight: bold;">Duració:</strong>
                                {{ $ticket->screening->movie->duration ?? 'N/A' }} minuts
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información de asiento y cliente -->
                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1; min-width: 200px; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
                        <h4 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Informació del Seient</h4>
                        <p style="margin-bottom: 10px;">
                            <strong style="font-weight: bold;">Número:</strong>
                            {{ $ticket->seat->seat_number ?? 'N/A' }}
                        </p>
                        <p style="margin-bottom: 10px;">
                            <strong style="font-weight: bold;">Fila:</strong>
                            {{ $ticket->seat->row ?? 'N/A' }}
                        </p>
                        <p style="margin-bottom: 10px;">
                            <strong style="font-weight: bold;">Columna:</strong>
                            {{ $ticket->seat->column ?? 'N/A' }}
                        </p>
                    </div>

                    <div style="flex: 1; min-width: 200px; background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
                        <h4 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Informació del Client</h4>
                        <p style="margin-bottom: 10px;">
                            <strong style="font-weight: bold;">Nom:</strong>
                            {{ $ticket->user->name ?? 'N/A' }}
                        </p>
                        <p style="margin-bottom: 10px;">
                            <strong style="font-weight: bold;">Email:</strong>
                            {{ $ticket->user->email ?? 'N/A' }}
                        </p>
                    </div>
                </div>

                <!-- Información de pago -->
                <div style="background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Informació de Pagament</h4>
                    <p style="margin-bottom: 10px; font-size: 18px;">
                        <strong style="font-weight: bold;">Preu:</strong>
                        <span style="font-size: 20px; color: #800040; font-weight: bold;">{{ number_format($ticket->price, 2) }} €</span>
                    </p>
                </div>

                <!-- Botón para eliminar -->
                <div style="text-align: center; margin-top: 25px; padding-top: 15px; border-top: 1px solid #eee;">
                    <button type="button" style="background-color: #dc3545; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-size: 14px;" onclick="if(confirm('Estàs segur que vols eliminar aquesta entrada?')) { document.getElementById('delete-form').submit(); }">
                        Eliminar Entrada
                    </button>
                    <form id="delete-form" action="{{ url('admin/tickets/' . $ticket->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection