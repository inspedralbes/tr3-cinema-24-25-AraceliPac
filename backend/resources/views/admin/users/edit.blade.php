@extends('layouts.admin')

@section('title', 'Editar Usuari')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 style="color: #333; margin-bottom: 20px;">Editar Usuari</h2>
        </div>
    </div>

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

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nom -->
                    <div style="margin-bottom: 20px;">
                        <label for="name" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Nom</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="Introdueix el nom"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('name') border-color: #dc3545; @enderror">
                        @error('name')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cognoms -->
                    <div style="margin-bottom: 20px;">
                        <label for="last_name" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Cognoms</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required placeholder="Introdueix els cognoms"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('last_name') border-color: #dc3545; @enderror">
                        @error('last_name')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom: 20px;">
                        <label for="email" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Correu electrònic</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="exemple@correu.com"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('email') border-color: #dc3545; @enderror">
                        @error('email')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                        <!-- Telèfon -->
                        <div style="flex: 1; min-width: 200px;">
                            <label for="phone" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Telèfon</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Introdueix el telèfon"
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('phone') border-color: #dc3545; @enderror">
                            @error('phone')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div style="flex: 1; min-width: 200px;">
                            <label for="role_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Rol</label>
                            <select id="role_id" name="role_id" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('role_id') border-color: #dc3545; @enderror">
                                <option value="">Selecciona un rol</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('role_id')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Imatge -->
                    <div style="margin-bottom: 20px;">
                        <label for="image" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Imatge de perfil</label>
                        <input type="text" id="image" name="image" value="{{ old('image', $user->image) }}" placeholder="URL de la imatge"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('image') border-color: #dc3545; @enderror">
                        @error('image')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror

                        @if($user->image)
                        <div style="margin-top: 10px; text-align: center;">
                            <img src="{{ $user->image }}" alt="{{ $user->name }}" style="max-height: 100px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                        @endif
                    </div>

                    <!-- Canvi de contrasenya (opcional) -->
                    <div style="margin-bottom: 20px; background-color: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #D4AF37;">
                        <h5 style="margin-top: 0; margin-bottom: 15px; color: #800040; font-weight: bold;">Canvi de contrasenya (opcional)</h5>

                        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                            <!-- Nova contrasenya -->
                            <div style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                                <label for="password" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Nova contrasenya</label>
                                <input type="password" id="password" name="password" placeholder="Deixa en blanc per mantenir l'actual"
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('password') border-color: #dc3545; @enderror">
                                @error('password')
                                <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmació de contrasenya -->
                            <div style="flex: 1; min-width: 200px; margin-bottom: 15px;">
                                <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Confirmació de contrasenya</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirma la nova contrasenya"
                                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                            </div>
                        </div>
                    </div>

                    <!-- Botons -->
                    <div style="display: flex; justify-content: flex-start; padding-top: 15px; border-top: 1px solid #eee; margin-top: 30px;">
                        <button type="submit" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-size: 14px;">
                            <i class="fas fa-save me-2"></i>Guardar Canvis
                        </button>
                        <a href="{{ route('admin.users.index') }}" style="background-color: #6c757d; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">
                            <i class="fas fa-arrow-left me-2"></i>Tornar al Llistat
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection