@extends('layouts.admin')

@section('title', 'Detall de Projecció')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Detall de Projecció</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/screenings/' . $screening->id . '/edit') }}" style="display: inline-block; margin-left: 50px;  background-color: #800040; color: #FFFFFF; margin-right: 10px; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; font-size: 14px;">
                Editar Projecció
            </a>
            <a href="{{ url('admin/screenings') }}" style="display: inline-block; background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; font-size: 14px;">
                Tornar al llistat
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div style="background-color: #fff; margin-top: 50px; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="margin-bottom: 20px;">
                    <h3 style="color: #800040; margin-bottom: 10px;">{{ $screening->movie->title ?? 'Pel·lícula no disponible' }}</h3>
                    <hr style="border: none; height: 2px; background-color: #D4AF37; margin: 15px 0;">
                </div>

                <div style="display: flex; flex-wrap: wrap; margin-bottom: 20px;">
                    <div style="width: 50%; padding-right: 10px;">
                        <p><strong style="font-weight: bold;">Data:</strong> {{ date('d/m/Y', strtotime($screening->screening_date)) }}</p>
                    </div>
                    <div style="width: 50%; padding-left: 10px;">
                        <p><strong style="font-weight: bold;">Hora:</strong> {{ date('H:i', strtotime($screening->screening_time)) }}</p>
                    </div>
                </div>

                <div style="display: flex; flex-wrap: wrap; margin-bottom: 20px;">
                    <div style="width: 50%; padding-right: 10px;">
                        <p>
                            <strong style="font-weight: bold;">Dia de l'espectador:</strong>
                            @if($screening->is_special_day)
                            <span style="display: inline-block; background-color: #28a745; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">Sí</span>
                            @else
                            <span style="display: inline-block; background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">No</span>
                            @endif
                        </p>
                    </div>
                    <div style="width: 50%; padding-left: 10px;">
                        <p>
                            <strong style="font-weight: bold;">Estat:</strong>
                            @if($screening->is_full)
                            <span style="display: inline-block; background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">Complet</span>
                            @else
                            <span style="display: inline-block; background-color: #28a745; color: white; padding: 5px 10px; border-radius: 8px; font-size: 12px;">Disponible</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <p><strong style="font-weight: bold;">Informació de la pel·lícula:</strong></p>
                    <div style="display: flex; flex-wrap: wrap;">
                        <div style="width: 30%; padding-right: 15px;">
                            @if(isset($screening->movie->image) && $screening->movie->image)
                            <img src="{{ $screening->movie->image }}" alt="{{ $screening->movie->title }}" style="max-width: 100%; height: auto; max-height: 200px; border-radius: 8px; display: block; margin-bottom: 15px;">
                            @else
                            <div style="width: 100%; height: 150px; background-color: #e0e0e0; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                <span>Imatge no disponible</span>
                            </div>
                            @endif
                        </div>
                        <div style="width: 70%; padding-left: 15px;">
                            @if(isset($screening->movie))
                            <p style="margin-bottom: 10px;"><strong style="font-weight: bold;">Gènere:</strong> {{ $screening->movie->genre->name ?? 'No disponible' }}</p>
                            <p style="margin-bottom: 10px;"><strong style="font-weight: bold;">Director:</strong> {{ $screening->movie->director->name ?? '' }} {{ $screening->movie->director->lastname ?? '' }}</p>
                            <p style="margin-bottom: 10px;"><strong style="font-weight: bold;">Any:</strong> {{ $screening->movie->release_year ?? 'No disponible' }}</p>
                            <p style="margin-bottom: 10px;"><strong style="font-weight: bold;">Duració:</strong> {{ $screening->movie->duration ?? 'No disponible' }} min</p>
                            @else
                            <p>Informació de la pel·lícula no disponible</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                    <button type="button" style="background-color: #dc3545; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-size: 14px;" onclick="if(confirm('Estàs segur que vols eliminar aquesta projecció?')) { document.getElementById('delete-form').submit(); }">
                        Eliminar Projecció
                    </button>
                    <form id="delete-form" action="{{ url('admin/screenings/' . $screening->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection