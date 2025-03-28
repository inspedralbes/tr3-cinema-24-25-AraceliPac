@extends('layouts.admin')

@section('title', 'Editar Gènere')

@section('content')
<div style="margin: 20px;">
    <div style="margin-bottom: 20px;">
        <h1 style="color: #800040; font-size: 24px; margin-bottom: 5px;">Editar Gènere</h1>
        <p style="color: #555; margin: 0;">Modifica la informació del gènere "{{ $genre->name }}".</p>
    </div>

    <!-- Formulario de edición -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px;">
        <form action="{{ route('admin.settings.genres.update', $genre->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Nom*</label>
                <input type="text" id="name" name="name" value="{{ old('name', $genre->name) }}" required
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('name') border-color: #dc3545; @enderror">
                <small style="display: block; color: #6c757d; margin-top: 5px;">Ha de ser únic i descriptiu (p. ex. Drama, Acció, Comèdia)</small>
                @error('name')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Descripción -->
            <div style="margin-bottom: 20px;">
                <label for="description" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Descripció</label>
                <textarea id="description" name="description" rows="5"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('description') border-color: #dc3545; @enderror">{{ old('description', $genre->description) }}</textarea>
                <small style="display: block; color: #6c757d; margin-top: 5px;">Breu descripció de les característiques d'aquest gènere cinematogràfic</small>
                @error('description')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Botones -->
            <div style="display: flex; justify-content: flex-start; padding-top: 15px; border-top: 1px solid #eee; margin-top: 30px;">
                <button type="submit" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-size: 14px;">
                    Actualitzar Gènere
                </button>
                <a href="{{ route('admin.settings.genres.index') }}" style="background-color: #6c757d; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">Cancel·lar</a>
            </div>
        </form>
    </div>
</div>
@endsection