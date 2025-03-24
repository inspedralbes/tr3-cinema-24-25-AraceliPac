@extends('layouts.admin')

@section('title', 'Afegir Nova Pel·lícula')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-lg" style="border: none; border-radius: 15px; overflow: hidden; border-top: 5px solid #D4AF37;">
                <div class="card-header py-3" style="background-color: #800040; color: #FFFFFF; text-align: center;">
                    <h3 class="mb-0 font-weight-bold"><i class="fas fa-film me-2"></i>Afegir Nova Pel·lícula</h3>
                </div>
                <div class="card-body p-4 bg-light">
                    @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.movies.store') }}" method="POST">
                        @csrf

                        <!-- Títol -->
                        <div class="form-group mb-4">
                            <label for="title" class="form-label fw-bold">Títol</label>
                            <input type="text" class="form-control form-control-lg shadow-sm" id="title" name="title" value="{{ old('title') }}" required placeholder="Introdueix el títol">
                        </div>

                        <!-- Any d'estrena -->
                        <div class="form-group mb-4">
                            <label for="release_year" class="form-label fw-bold">Any d'estrena</label>
                            <input type="number" class="form-control shadow-sm" id="release_year" name="release_year" value="{{ old('release_year') }}" min="1900" max="{{ date('Y') + 1 }}" placeholder="Any">
                        </div>

                        <!-- Duració -->
                        <div class="form-group mb-4">
                            <label for="duration" class="form-label fw-bold">Duració (minuts)</label>
                            <input type="number" class="form-control shadow-sm" id="duration" name="duration" value="{{ old('duration') }}" min="1" placeholder="Minuts">
                        </div>

                        <!-- Descripció -->
                        <div class="form-group mb-4">
                            <label for="description" class="form-label fw-bold">Descripció</label>
                            <textarea class="form-control shadow-sm" id="description" name="description" rows="4" placeholder="Escriu una breu descripció de la pel·lícula">{{ old('description') }}</textarea>
                        </div>

                        <!-- Gènere -->
                        <div class="form-group mb-4">
                            <label for="genre_id" class="form-label fw-bold">Gènere</label>
                            <select class="form-select shadow-sm" id="genre_id" name="genre_id" required>
                                <option value="">Selecciona un gènere</option>
                                @foreach($genres as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Director -->
                        <div class="form-group mb-4">
                            <label for="director_id" class="form-label fw-bold">Director</label>
                            <select class="form-select shadow-sm" id="director_id" name="director_id" required>
                                <option value="">Selecciona un director</option>
                                @foreach($directors as $director)
                                <option value="{{ $director->id }}" {{ old('director_id') == $director->id ? 'selected' : '' }}>
                                    {{ $director->name }} {{ $director->lastname }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Classificació -->
                        <div class="form-group mb-4">
                            <label for="rating" class="form-label fw-bold">Classificació</label>
                            <select class="form-select shadow-sm" id="rating" name="rating">
                                <option value="">Selecciona classificació</option>
                                <option value="0" {{ old('rating') == '0' ? 'selected' : '' }}>0 Estrelles</option>
                                <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Estrella</option>
                                <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Estrelles</option>
                                <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Estrelles</option>
                                <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Estrelles</option>
                                <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Estrelles</option>
                            </select>
                        </div>

                        <!-- URL de la imatge -->
                        <div class="form-group mb-4">
                            <label for="image" class="form-label fw-bold">URL de la imatge</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text" style="background-color: #D4AF37; color: #FFFFFF;"><i class="fas fa-image"></i></span>
                                <input type="text" class="form-control" id="image" name="image" value="{{ old('image') }}" placeholder="https://exemple.com/imatge.jpg">
                            </div>
                        </div>

                        <!-- URL del tràiler -->
                        <div class="form-group mb-4">
                            <label for="trailer" class="form-label fw-bold">URL del tràiler</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text" style="background-color: #D4AF37; color: #FFFFFF;"><i class="fas fa-video"></i></span>
                                <input type="text" class="form-control" id="trailer" name="trailer" value="{{ old('trailer') }}" placeholder="https://exemple.com/trailer">
                            </div>
                        </div>

                        <!-- Actors -->
                        <div class="form-group mb-4">
                            <label for="actor_ids" class="form-label fw-bold">Actors</label>
                            <select class="form-select shadow-sm" id="actor_ids" name="actor_ids[]" multiple style="height: 150px;">
                                @foreach($actors as $actor)
                                <option value="{{ $actor->id }}" {{ (is_array(old('actor_ids')) && in_array($actor->id, old('actor_ids'))) ? 'selected' : '' }}>
                                    {{ $actor->name }} {{ $actor->lastname }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted mt-2"><i class="fas fa-info-circle me-1"></i>Manté premuda la tecla Ctrl (o Command en Mac) per seleccionar múltiples actors.</small>
                        </div>

                        <!-- Botons -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-lg px-4 py-4 shadow-sm" style="background-color: #800040; color: #FFFFFF; border: none;">
                                <i class="fas fa-save me-2"></i>Guardar Pel·lícula
                            </button>
                            <a href="{{ route('admin.movies.index') }}" class="btn btn-lg btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Cancel·lar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Estils personalitzats */
    .form-control:focus,
    .form-select:focus {
        border-color: #D4AF37;
        box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        transition: all 0.2s;
    }

    select[multiple] option:checked {
        background-color: #800040 linear-gradient(0deg, #800040 0%, #800040 100%);
    }

    /* Adaptació per a dispositius mòbils */
    @media (max-width: 768px) {
        .card-header h3 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('scripts')
<script>
    // Script opcional per millorar la selecció múltiple d'actors
    document.addEventListener('DOMContentLoaded', function() {
        // Podria afegir-se un plugin com Select2 o similar per millorar l'experiència
    });
</script>
@endsection
@endsection