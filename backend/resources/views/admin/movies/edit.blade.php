@extends('layouts.admin')

@section('title', 'Editar Pel·lícula')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 style="color: #800040; margin-bottom: 20px;">Editar Pel·lícula: {{ $movie->title }}</h2>
        </div>
    </div>

    @if(session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('error') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #721c24;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-top: 3px solid #D4AF37;">
                @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                        <div style="flex: 2; min-width: 250px;">
                            <label for="title" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Títol</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $movie->title) }}" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('title') border-color: #dc3545; @enderror">
                            @error('title')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="flex: 1; min-width: 120px;">
                            <label for="release_year" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Any d'estrena</label>
                            <input type="number" id="release_year" name="release_year" value="{{ old('release_year', $movie->release_year) }}" min="1900" max="{{ date('Y') + 1 }}"
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('release_year') border-color: #dc3545; @enderror">
                            @error('release_year')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="flex: 1; min-width: 120px;">
                            <label for="duration" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Duració (minuts)</label>
                            <input type="number" id="duration" name="duration" value="{{ old('duration', $movie->duration) }}" min="1"
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('duration') border-color: #dc3545; @enderror">
                            @error('duration')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="description" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Descripció</label>
                        <textarea id="description" name="description" rows="3"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('description') border-color: #dc3545; @enderror">{{ old('description', $movie->description) }}</textarea>
                        @error('description')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="genre_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Gènere</label>
                            <select id="genre_id" name="genre_id" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('genre_id') border-color: #dc3545; @enderror">
                                <option value="">Selecciona un gènere</option>
                                @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id', $movie->genre_id) == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('genre_id')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="flex: 1; min-width: 200px;">
                            <label for="director_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Director</label>
                            <select id="director_id" name="director_id" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('director_id') border-color: #dc3545; @enderror">
                                <option value="">Selecciona un director</option>
                                @foreach($directors as $director)
                                <option value="{{ $director->id }}" {{ old('director_id', $movie->director_id) == $director->id ? 'selected' : '' }}>
                                    {{ $director->name }} {{ $director->lastname }}
                                </option>
                                @endforeach
                            </select>
                            @error('director_id')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="flex: 1; min-width: 200px;">
                            <label for="rating" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Classificació</label>
                            <select id="rating" name="rating"
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('rating') border-color: #dc3545; @enderror">
                                <option value="">Selecciona classificació</option>
                                <option value="G" {{ old('rating', $movie->rating) == 'G' ? 'selected' : '' }}>G (Tots els públics)</option>
                                <option value="PG" {{ old('rating', $movie->rating) == 'PG' ? 'selected' : '' }}>PG (Guia paternal suggerida)</option>
                                <option value="PG-13" {{ old('rating', $movie->rating) == 'PG-13' ? 'selected' : '' }}>PG-13 (No recomanada per a menors de 13 anys)</option>
                                <option value="R" {{ old('rating', $movie->rating) == 'R' ? 'selected' : '' }}>R (Restringida)</option>
                                <option value="NC-17" {{ old('rating', $movie->rating) == 'NC-17' ? 'selected' : '' }}>NC-17 (No admès per a menors de 17 anys)</option>
                            </select>
                            @error('rating')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- URL de la imatge -->
                    <div style="margin-bottom: 20px;">
                        <label for="image" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">URL de la imatge</label>
                        <input type="text" id="image" name="image" value="{{ old('image', $movie->image) }}" placeholder="https://exemple.com/imatge.jpg"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('image') border-color: #dc3545; @enderror">
                        @error('image')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- URL del tràiler -->
                    <div style="margin-bottom: 20px;">
                        <label for="trailer" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">URL del tràiler</label>
                        <input type="text" id="trailer" name="trailer" value="{{ old('trailer', $movie->trailer) }}" placeholder="https://exemple.com/trailer"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('trailer') border-color: #dc3545; @enderror">
                        @error('trailer')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Actors (si es necesario) -->
                    <div style="margin-bottom: 20px;">
                        <label for="actor_ids" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Actors</label>
                        <select id="actor_ids" name="actor_ids[]" multiple
                            style="width: 100%; height: 150px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('actor_ids') border-color: #dc3545; @enderror">
                            @foreach($actors ?? [] as $actor)
                            <option value="{{ $actor->id }}" {{ (is_array(old('actor_ids')) && in_array($actor->id, old('actor_ids'))) || (empty(old('actor_ids')) && in_array($actor->id, $movieActorIds ?? [])) ? 'selected' : '' }}>
                                {{ $actor->name }} {{ $actor->lastname }}
                            </option>
                            @endforeach
                        </select>
                        <small style="display: block; color: #6c757d; margin-top: 5px;">Manté premuda la tecla Ctrl (o Command en Mac) per seleccionar múltiples actors.</small>
                        @error('actor_ids')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; justify-content: center; padding-top: 15px; border-top: 1px solid #eee; margin-top: 30px;">
                        <button type="submit" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-size: 14px;">
                            Guardar Canvis
                        </button>
                        <a href="{{ route('admin.movies.index') }}" style="background-color: #6c757d; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">Cancel·lar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection