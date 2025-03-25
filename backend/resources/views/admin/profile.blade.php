@extends('layouts.admin')

@section('title', 'El Meu Perfil')

@section('content')
<style>
    .container-fluid {
        padding: 20px;
    }

    .profile-title {
        color: #333;
        margin-bottom: 20px;
    }

    .center-container {
        display: flex;
        justify-content: center;
        align-items: center;
        /* height: 100vh; */
        margin: auto;
    }

    .profile-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-top: 3px solid #D4AF37;
        text-align: center;
    }

    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #D4AF37;
        margin-bottom: 15px;
    }

    .profile-initial {
        width: 150px;
        height: 150px;
        background-color: #800040;
        color: #FFFFFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        margin: 0 auto 15px;
    }

    .profile-name {
        margin: 0 0 5px 0;
        color: #333;
    }

    .role-badge span {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        color: white;
        font-weight: 500;
    }

    .role-1 {
        background-color: #800040;
    }

    .role-2 {
        background-color: #D4AF37;
    }

    .role-default {
        background-color: #6c757d;
    }

    .profile-info {
        margin-top: 20px;
        text-align: left;
    }

    .profile-info div {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .icon {
        color: #800040;
        width: 20px;
        margin-right: 10px;
    }

    .password-card {
        margin-top: 20px;
        padding: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 10px;
    }

    .btn-save {
        background-color: #800040;
        color: #FFFFFF;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <!-- <h2 class="profile-title">El Meu Perfil</h2> -->
        </div>
    </div>

    <div class="row">
        <div class="center-container">
            <div class="profile-card">
                @if(Auth::user()->image)
                <img src="{{ Auth::user()->image }}" alt="{{ Auth::user()->name }}" class="profile-img">
                @else
                <div class="profile-initial">{{ substr(Auth::user()->name, 0, 1) }}</div>
                @endif

                <h3 class="profile-name">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h3>

                <div class="role-badge">
                    @if(Auth::user()->role)
                    <span class="role-{{ Auth::user()->role->id }}">{{ Auth::user()->role->name }}</span>
                    @else
                    <span class="role-default">Sense rol</span>
                    @endif
                </div>

                <div class="profile-info">
                    <div><i class="fas fa-envelope icon"></i> {{ Auth::user()->email }}</div>
                    <div><i class="fas fa-phone icon"></i> {{ Auth::user()->phone ?? 'No especificat' }}</div>
                    <div><i class="fas fa-clock icon"></i> Membre des de {{ Auth::user()->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

        </div>

        <div class="profile-card password-card">
            <h4><i class="fas fa-lock icon"></i>Canviar Contrasenya</h4>

            @if(session('password_success'))
            <div class="alert-success">{{ session('password_success') }}</div>
            @endif

            @if($errors->updatePassword->any())
            <div class="alert-error">
                <ul>
                    @foreach($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <label for="current_password">Contrasenya actual</label>
                <input type="password" id="current_password" name="current_password" required>

                <label for="password">Nova contrasenya</label>
                <input type="password" id="password" name="password" required>

                <label for="password_confirmation">Confirma la nova contrasenya</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>

                <button type="submit" class="btn-save"><i class="fas fa-save"></i> Actualitzar Contrasenya</button>
            </form>
        </div>
    </div>
</div>
@endsection