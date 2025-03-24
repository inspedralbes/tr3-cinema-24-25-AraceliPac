@extends('layouts.admin')

@section('title', 'Editar Pel·lícula')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <h2 class="fw-bold text-uppercase" style="color: #800040;">Editar Pel·lícula: {{ $movie->title }}</h2>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label fw-bold">Títol</label>
                                <input type="text" class="form-control shadow-sm @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $movie->title) }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="release_year" class="form-label fw-bold">Any d'estrena</label>
                                <input type="number" class="form-control shadow-sm @error('release_year') is-invalid @enderror" id="release_year" name="release_year" value="{{ old('release_year', $movie->release_year) }}" min="1900" max="{{ date('Y') + 1 }}">
                                @error('release_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="duration" class="form-label fw-bold">Duració (minuts)</label>
                                <input type="number" class="form-control shadow-sm @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $movie->duration) }}" min="1">
                                @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Descripció</label>
                            <textarea class="form-control shadow-sm @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $movie->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="genre_id" class="form-label fw-bold">Gènere</label>
                                <select class="form-control shadow-sm @error('genre_id') is-invalid @enderror" id="genre_id" name="genre_id" required>
                                    <option value="">Selecciona un gènere</option>
                                    @foreach($genres as $genre)
                                    <option value="{{ $genre->id }}" {{ old('genre_id', $movie->genre_id) == $genre->id ? 'selected' : '' }}>
                                        {{ $genre->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('genre_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="director_id" class="form-label fw-bold">Director</label>
                                <select class="form-control shadow-sm @error('director_id') is-invalid @enderror" id="director_id" name="director_id" required>
                                    <option value="">Selecciona un director</option>
                                    @foreach($directors as $director)
                                    <option value="{{ $director->id }}" {{ old('director_id', $movie->director_id) == $director->id ? 'selected' : '' }}>
                                        {{ $director->name }} {{ $director->lastname }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('director_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="rating" class="form-label fw-bold">Classificació</label>
                                <select class="form-control shadow-sm @error('rating') is-invalid @enderror" id="rating" name="rating">
                                    <option value="">Selecciona classificació</option>
                                    <option value="G" {{ old('rating', $movie->rating) == 'G' ? 'selected' : '' }}>G (Tots els públics)</option>
                                    <option value="PG" {{ old('rating', $movie->rating) == 'PG' ? 'selected' : '' }}>PG (Guia paternal suggerida)</option>
                                    <option value="PG-13" {{ old('rating', $movie->rating) == 'PG-13' ? 'selected' : '' }}>PG-13 (No recomanada per a menors de 13 anys)</option>
                                    <option value="R" {{ old('rating', $movie->rating) == 'R' ? 'selected' : '' }}>R (Restringida)</option>
                                    <option value="NC-17" {{ old('rating', $movie->rating) == 'NC-17' ? 'selected' : '' }}>NC-17 (No admès per a menors de 17 anys)</option>
                                </select>
                                @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-lg px-4 py-3" style="background-color: #800040; color: #FFFFFF;">
                                <i class="fas fa-save me-2"></i>Guardar Canvis
                            </button>
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary btn-lg px-4 py-3">
                                <i class="fas fa-times me-2"></i>Cancel·lar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection