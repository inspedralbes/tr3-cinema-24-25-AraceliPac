@extends('layouts.admin')

@section('title', 'Gestió de Pel·lícules')

@section('content')
<style>
    .table th,
    .table td {
        padding: 12px;
        text-align: center;
    }

    .table th {
        background-color: #f8f9fa;
    }

    .dashboard-card {
        padding: 20px;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-custom {
        background-color: #800040;
        color: #fff;
        border-radius: 8px;
        padding: 10px 16px;
    }

    .btn-custom:hover {
        background-color: #a00050;
        color: #fff;
    }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Llistat de Pel·lícules</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ url('admin/movies/create') }}" class="btn btn-custom">
                Nova Pel·lícula
            </a>
        </div>
    </div>

    <!-- Filtros (opcional) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card">
                <h5 style="color: #800040;">FILTRES</h5>
                <form action="{{ url('admin/movies') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="title" class="form-label">Títol</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ request('title') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="genre" class="form-label">Gènere</label>
                        <select class="form-select" id="genre" name="genre_id">
                            <option value="">Tots els gèneres</option>
                            @foreach($genres ?? [] as $genre)
                            <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="year" class="form-label">Any</label>
                        <input type="number" class="form-control" id="year" name="year" value="{{ request('year') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-custom w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de películas -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Imatge</th>
                                <th>Títol</th>
                                <th>Gènere</th>
                                <th>Director</th>
                                <th>Any</th>
                                <th>Duració</th>
                                <th>Accions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movies ?? [] as $movie)
                            <tr>
                                <td>{{ $movie->id }}</td>
                                <td>
                                    @if($movie->image)
                                    <img src="{{ $movie->image }}" alt="{{ $movie->title }}" style="width: 50px; height: auto;">
                                    @else
                                    <div class="d-flex justify-content-center align-items-center" style="width: 50px; height: 75px; background-color: #e0e0e0;">
                                        <span>No img</span>
                                    </div>
                                    @endif
                                </td>
                                <td>{{ $movie->title }}</td>
                                <td>{{ $movie->genre->name ?? 'N/A' }}</td>
                                <td>{{ $movie->director->name ?? '' }} {{ $movie->director->lastname ?? '' }}</td>
                                <td>{{ $movie->release_year }}</td>
                                <td>{{ $movie->duration }} min</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ url('admin/movies/' . $movie->id) }}" class="btn btn-sm" style="background-color: #D4AF37; border-radius: 8px; padding: 10px 16px;" title="Veure">
                                            Veure
                                        </a>
                                        <a href="{{ url('admin/movies/' . $movie->id . '/edit') }}" class="btn btn-sm btn-custom" title="Editar">
                                            Editar
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" style="border-radius: 8px; padding: 10px 16px;" title="Eliminar"
                                            onclick="if(confirm('Estàs segur que vols eliminar aquesta pel·lícula?')) { 
                                                document.getElementById('delete-form-{{ $movie->id }}').submit(); 
                                            }">
                                            Eliminar
                                        </button>
                                        <form id="delete-form-{{ $movie->id }}" action="{{ url('admin/movies/' . $movie->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No s'han trobat pel·lícules</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Paginación -->
                @if(isset($movies) && method_exists($movies, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $movies->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection