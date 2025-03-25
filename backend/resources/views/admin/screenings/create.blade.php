@extends('layouts.admin')

@section('title', 'Crear Nova Projecció')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 style="color: #333; margin-bottom: 20px;">Afegir Nova Projecció</h2>
        </div>
    </div>

    @if ($errors->any())
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <form action="{{ route('admin.screenings.store') }}"  method="POST">
                    @csrf

                    <div style="margin-bottom: 20px;">
                        <div style="margin-bottom: 25px;">
                            <label for="movie_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Pel·lícula</label>
                            <select id="movie_id" name="movie_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff; color: #333; @error('movie_id') border-color: #dc3545; @enderror">
                                <option value="">Selecciona una pel·lícula</option>
                                @foreach($movies ?? [] as $movie)
                                <option value="{{ $movie->id }}" {{ old('movie_id') == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->title }}
                                </option>
                                @endforeach
                            </select>
                            @error('movie_id')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; margin-bottom: 20px;">
                        <div style="flex: 1; padding-right: 10px; min-width: 200px;">
                            <div style="margin-bottom: 15px;">
                                <label for="screening_date" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Data de projecció</label>
                                <input type="date" id="screening_date" name="screening_date" value="{{ old('screening_date') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('screening_date') border-color: #dc3545; @enderror">
                                @error('screening_date')
                                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="flex: 1; padding-left: 10px; min-width: 200px;">
                            <div style="margin-bottom: 15px;">
                                <label for="screening_time" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Hora de projecció</label>
                                <input type="time" id="screening_time" name="screening_time" value="{{ old('screening_time') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('screening_time') border-color: #dc3545; @enderror">
                                @error('screening_time')
                                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; margin-bottom: 30px;">
                        <div style="flex: 1; padding-right: 10px; min-width: 200px;">
                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; align-items: center;">
                                    <input type="checkbox" id="is_special_day" name="is_special_day" value="1" {{ old('is_special_day') ? 'checked' : '' }} style="margin-right: 10px; width: 18px; height: 18px; @error('is_special_day') border-color: #dc3545; @enderror">
                                    <label for="is_special_day" style="margin-bottom: 0; font-weight: bold; color: #333;">És dia de l'espectador</label>
                                </div>
                                @error('is_special_day')
                                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div style="flex: 1; padding-left: 10px; min-width: 200px;">
                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; align-items: center;">
                                    <input type="checkbox" id="is_full" name="is_full" value="1" {{ old('is_full') ? 'checked' : '' }} style="margin-right: 10px; width: 18px; height: 18px; @error('is_full') border-color: #dc3545; @enderror">
                                    <label for="is_full" style="margin-bottom: 0; font-weight: bold; color: #333;">Marcar com a complet</label>
                                </div>
                                @error('is_full')
                                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-start; padding-top: 15px; border-top: 1px solid #eee;">
                        <button type="submit" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-size: 14px;">
                            Guardar Projecció
                        </button>
                        <a href="{{ url('admin/screenings') }}" style="background-color: #6c757d; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">Cancel·lar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection