@extends('layouts.admin')

@section('title', 'El Meu Perfil')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 style="color: #333; margin-bottom: 20px;">El Meu Perfil</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Targeta d'informació personal -->
            <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-top: 3px solid #D4AF37; text-align: center; height: 100%;">
                @if(Auth::user()->image)
                <img src="{{ Auth::user()->image }}" alt="{{ Auth::user()->name }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #D4AF37; margin-bottom: 15px;">
                @else
                <div style="width: 150px; height: 150px; background-color: #800040; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 4rem;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                @endif

                <h3 style="margin: 0 0 5px 0; color: #333;">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h3>

                <div style="margin: 15px 0;">
                    @if(Auth::user()->role)
                    @if(Auth::user()->role->id == 1)
                    <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #800040; color: white; font-weight: 500;">
                        {{ Auth::user()->role->name }}
                    </span>
                    @elseif(Auth::user()->role->id == 2)
                    <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #D4AF37; color: white; font-weight: 500;">
                        {{ Auth::user()->role->name }}
                    </span>
                    @else
                    <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #6c757d; color: white; font-weight: 500;">
                        {{ Auth::user()->role->name }}
                    </span>
                    @endif
                    @else
                    <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #6c757d; color: white; font-weight: 500;">
                        Sense rol
                    </span>
                    @endif
                </div>

                <div style="margin-top: 20px; text-align: left;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-envelope" style="color: #800040; width: 20px; margin-right: 10px;"></i>
                        <span>{{ Auth::user()->email }}</span>
                    </div>

                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <i class="fas fa-phone" style="color: #800040; width: 20px; margin-right: 10px;"></i>
                        <span>{{ Auth::user()->phone ?? 'No especificat' }}</span>
                    </div>

                    <div style="display: flex; align-items: center;">
                        <i class="fas fa-clock" style="color: #800040; width: 20px; margin-right: 10px;"></i>
                        <span>Membre des de {{ Auth::user()->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>


            </div>
        </div>


        <!-- Targeta de canvi de contrasenya -->
        <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-top: 3px solid #D4AF37;">
            <h4 style="color: #333; margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                <i class="fas fa-lock me-2" style="color: #800040;"></i>Canviar Contrasenya
            </h4>

            @if(session('password_success'))
            <div style="background-color: #d4edda; color: #155724; padding: 12px 20px; border: 1px solid #c3e6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
                {{ session('password_success') }}
                <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #155724;" onclick="this.parentElement.style.display='none'">&times;</button>
            </div>
            @endif

            @if($errors->updatePassword->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 20px;">
                    <label for="current_password" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Contrasenya actual</label>
                    <input type="password" id="current_password" name="current_password" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('current_password', 'updatePassword') border-color: #dc3545; @enderror">
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1; min-width: 200px;">
                        <label for="password" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Nova contrasenya</label>
                        <input type="password" id="password" name="password" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('password', 'updatePassword') border-color: #dc3545; @enderror">
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Confirma la nova contrasenya</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-size: 14px;">
                        <i class="fas fa-save me-2"></i>Actualitzar Contrasenya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection