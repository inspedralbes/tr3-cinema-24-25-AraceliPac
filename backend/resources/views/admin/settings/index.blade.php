@extends('layouts.admin')

@section('title', 'Configuració')

@section('content')
<div style="margin: 20px;">
    <h1 style="color: #800040; margin-bottom: 20px; font-size: 24px; border-bottom: 2px solid #800040; padding-bottom: 10px;">Configuració del Sistema</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <!-- Gestión de Actores -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Gestió d'Actors</h3>
            </div>
            <div style="padding: 20px;">
                <p style="color: #555; margin-bottom: 15px;">Administra els actors que apareixen a les pel·lícules.</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="{{ route('admin.settings.actors.index') }}" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">👨‍🎬</span> Llistat d'actors
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.actors.create') }}" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">➕</span> Nou actor
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Gestión de Directores -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Gestió de Directors</h3>
            </div>
            <div style="padding: 20px;">
                <p style="color: #555; margin-bottom: 15px;">Administra els directors de les pel·lícules.</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="{{ route('admin.settings.directors.index') }}" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">🎬</span> Llistat de directors
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.directors.create') }}" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">➕</span> Nou director
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Gestión de Géneros -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Gestió de Gèneres</h3>
            </div>
            <div style="padding: 20px;">
                <p style="color: #555; margin-bottom: 15px;">Administra els gèneres cinematogràfics.</p>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="{{ route('admin.settings.genres.index') }}" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">🎭</span> Llistat de gèneres
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.genres.create') }}" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">➕</span> Nou gènere
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection