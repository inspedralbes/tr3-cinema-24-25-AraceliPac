@extends('layouts.admin')

@section('title', 'Nou Actor')

@section('content')
<div style="margin: 20px;">
    <div style="margin-bottom: 20px;">
        <h1 style="color: #800040; font-size: 24px; margin-bottom: 5px;">Crear Nou Actor</h1>
        <p style="color: #555; margin: 0;">Completa el formulari per afegir un nou actor.</p>
    </div>

    <!-- Formulario de creación -->
    <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px;">
        <form action="{{ route('admin.settings.actors.store') }}" method="POST">
            @csrf

            <!-- Nombre -->
            <div style="margin-bottom: 20px;">
                <label for="name" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Nom*</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('name') border-color: #dc3545; @enderror">
                @error('name')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Apellido -->
            <div style="margin-bottom: 20px;">
                <label for="lastname" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Cognom</label>
                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('lastname') border-color: #dc3545; @enderror">
                @error('lastname')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Fecha de nacimiento -->
            <div style="margin-bottom: 20px;">
                <label for="birth_date" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Data de Naixement</label>
                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('birth_date') border-color: #dc3545; @enderror">
                @error('birth_date')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nacionalidad -->
            <div style="margin-bottom: 20px;">
                <label for="nationality" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Nacionalitat</label>
                <input type="text" id="nationality" name="nationality" value="{{ old('nationality') }}" 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('nationality') border-color: #dc3545; @enderror">
                @error('nationality')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Biografía -->
            <div style="margin-bottom: 20px;">
                <label for="bio" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Biografia</label>
                <textarea id="bio" name="bio" rows="5" 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('bio') border-color: #dc3545; @enderror">{{ old('bio') }}</textarea>
                @error('bio')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- URL de imagen -->
            <div style="margin-bottom: 20px;">
                <label for="image" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">URL d'Imatge</label>
                <input type="text" id="image" name="image" value="{{ old('image') }}" 
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('image') border-color: #dc3545; @enderror">
                <small style="display: block; color: #6c757d; margin-top: 5px;">Enllaç a una imatge de l'actor (opcional)</small>
                @error('image')
                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Botones -->
            <div style="display: flex; justify-content: flex-start; padding-top: 15px; border-top: 1px solid #eee; margin-top: 30px;">
                <button type="submit" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-size: 14px;">
                    Guardar Actor
                </button>
                <a href="{{ route('admin.settings.actors.index') }}" style="background-color: #6c757d; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">Cancel·lar</a>
            </div>
        </form>
    </div>
</div>
@endsection