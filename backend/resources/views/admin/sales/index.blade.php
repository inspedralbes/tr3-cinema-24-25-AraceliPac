@extends('layouts.admin')

@section('content')
<div style="margin: 20px;">
    <h1 style="color: #800040; margin-bottom: 20px; font-size: 24px; border-bottom: 2px solid #800040; padding-bottom: 10px;">Registre de Vendes</h1>

    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
        <!-- Estadísticas generales -->
        <div style="flex: 1; min-width: 250px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
            <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Resum Total</h2>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #555;">Total recaptat:</span>
                <span style="font-weight: bold; color: #800040;">{{ number_format($totalSales, 2) }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #555;">Nombre d'entrades:</span>
                <span style="font-weight: bold;">{{ $totalTickets }}</span>
            </div>
        </div>

        <!-- Estadísticas de hoy -->
        <div style="flex: 1; min-width: 250px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px;">
            <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Avui</h2>
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #555;">Recaptat avui:</span>
                <span style="font-weight: bold; color: #800040;">{{ number_format($todaySales, 2) }} €</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #555;">Entrades venudes:</span>
                <span style="font-weight: bold;">{{ $todayTickets }}</span>
            </div>
        </div>
    </div>

    <!-- Enlaces a informes detallados -->
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
        <a href="{{ route('admin.sales.daily') }}" style="flex: 1; min-width: 250px; background-color: #800040; color: white; text-decoration: none; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            Informe Diari
        </a>
        <a href="{{ route('admin.sales.movie') }}" style="flex: 1; min-width: 250px; background-color: #800040; color: white; text-decoration: none; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            Informe per Pel·lícula
        </a>
        <a href="{{ route('admin.sales.screening') }}" style="flex: 1; min-width: 250px; background-color: #800040; color: white; text-decoration: none; padding: 15px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            Informe per Projecció
        </a>
    </div>

    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px;">
        <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Vendes dels Últims 7 Dies</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #ddd;">
                    <th style="padding: 10px; text-align: left; color: #555;">Data</th>
                    <th style="padding: 10px; text-align: right; color: #555;">Entrades</th>
                    <th style="padding: 10px; text-align: right; color: #555;">Import</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailySales as $day)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px; color: #333;">{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                    <td style="padding: 10px; text-align: right; color: #333;">{{ $day->tickets_count }}</td>
                    <td style="padding: 10px; text-align: right; color: #800040; font-weight: bold;">{{ number_format($day->total_sales, 2) }} €</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding: 10px; text-align: center; color: #777;">No hi ha vendes registrades en els últims 7 dies</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Top 5 películas -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 30px;">
        <h2 style="color: #800040; font-size: 18px; margin-bottom: 15px;">Top Pel·lícules</h2>
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
                    <td colspan="3" style="padding: 10px; text-align: center; color: #777;">No hi ha pel·lícules amb vendes registrades</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection