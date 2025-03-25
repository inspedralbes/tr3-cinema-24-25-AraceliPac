@extends('layouts.admin')

@section('content')
<div style="margin: 20px;">
    <h1 style="color: #800040; margin-bottom: 20px; font-size: 24px; border-bottom: 2px solid #800040; padding-bottom: 10px;">Informe Diari de Vendes</h1>

    <!-- Selector de fecha -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px;">
        <form action="{{ route('admin.sales.daily') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
            <div style="flex-grow: 1; min-width: 200px;">
                <label for="date" style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Seleccionar Data</label>
                <input type="date" id="date" name="date" value="{{ $date->format('Y-m-d') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
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

    <!-- Resumen del día -->
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
        <div style="flex: 1; min-width: 250px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
            <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Resum del {{ $date->format('d/m/Y') }}</h2>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #555;">Total recaptat:</span>
                <span style="font-weight: bold; color: #800040;">{{ number_format($daySales, 2) }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #555;">Nombre d'entrades:</span>
                <span style="font-weight: bold;">{{ $dayTickets }}</span>
            </div>
        </div>
    </div>

    <!-- Ventas por película -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px;">
        <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Vendes per Pel·lícula</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="padding: 10px; text-align: left; color: #555;">Pel·lícula</th>
                    <th style="padding: 10px; text-align: right; color: #555;">Entrades</th>
                    <th style="padding: 10px; text-align: right; color: #555;">Import</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movieSales as $movie)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px; color: #333;">{{ $movie->title }}</td>
                    <td style="padding: 10px; text-align: right; color: #333;">{{ $movie->tickets_count }}</td>
                    <td style="padding: 10px; text-align: right; color: #800040; font-weight: bold;">{{ number_format($movie->total_sales, 2) }} €</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 10px; text-align: center; color: #777;">No hi ha vendes per pel·lícules en aquesta data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Ventas por proyección -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
        <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Vendes per Projecció</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="padding: 10px; text-align: left; color: #555;">Pel·lícula</th>
                    <th style="padding: 10px; text-align: left; color: #555;">Sessió</th>
                    <th style="padding: 10px; text-align: right; color: #555;">Entrades</th>
                    <th style="padding: 10px; text-align: right; color: #555;">Import</th>
                </tr>
            </thead>
            <tbody>
                @forelse($screeningSales as $screening)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px; color: #333;">{{ $screening->title }}</td>
                    <td style="padding: 10px; color: #333;">{{ \Carbon\Carbon::parse($screening->screening_date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($screening->screening_time)->format('H:i') }}</td>
                    <td style="padding: 10px; text-align: right; color: #333;">{{ $screening->tickets_count }}</td>
                    <td style="padding: 10px; text-align: right; color: #800040; font-weight: bold;">{{ number_format($screening->total_sales, 2) }} €</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 10px; text-align: center; color: #777;">No hi ha vendes per projeccions en aquesta data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection