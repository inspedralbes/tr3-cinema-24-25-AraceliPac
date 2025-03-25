@extends('layouts.admin')

@section('title', 'Panell d\'Administració')

@section('content')
<div class="container-fluid">
    <!-- Resumen de estadísticas -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 25px;">
        <!-- Películas activas -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-top: 3px solid #D4AF37;">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Pel·lícules Actives</h3>
            </div>
            <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 32px; font-weight: bold; color: #333;">5</span>
                    <p style="margin: 5px 0 0; color: #666;">pel·lícules en cartellera</p>
                </div>
                <div style="font-size: 36px; color: #D4AF37;">🎬</div>
            </div>
        </div>

        <!-- Entradas vendidas -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-top: 3px solid #28a745;">
            <div style="background-color: #28a745; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Entrades Venudes (Avui)</h3>
            </div>
            <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 32px; font-weight: bold; color: #333;">42</span>
                    <p style="margin: 5px 0 0; color: #666;">entrades</p>
                </div>
                <div style="font-size: 36px; color: #28a745;">🎟️</div>
            </div>
        </div>

        <!-- Proyecciones programadas -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-top: 3px solid #17a2b8;">
            <div style="background-color: #17a2b8; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Projeccions Programades</h3>
            </div>
            <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 32px; font-weight: bold; color: #333;">15</span>
                    <p style="margin: 5px 0 0; color: #666;">projeccions</p>
                </div>
                <div style="font-size: 36px; color: #17a2b8;">📽️</div>
            </div>
        </div>

        <!-- Usuarios registrados -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); border-top: 3px solid #6c757d;">
            <div style="background-color: #6c757d; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Usuaris Registrats</h3>
            </div>
            <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 32px; font-weight: bold; color: #333;">124</span>
                    <p style="margin: 5px 0 0; color: #666;">usuaris</p>
                </div>
                <div style="font-size: 36px; color: #6c757d;">👥</div>
            </div>
        </div>
    </div>

    <!-- Secciones de gestión -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-bottom: 25px;">
        <!-- Gestión de películas -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Gestió de Pel·lícules</h3>
            </div>
            <div style="padding: 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="/admin/movies/create" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">➕</span> Nova pel·lícula
                        </a>
                    </li>
                    <li>
                        <a href="/admin/movies" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">📋</span> Llistat de pel·lícules
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Proyecciones -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Projeccions</h3>
            </div>
            <div style="padding: 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="/admin/screenings/create" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">➕</span> Nova projecció
                        </a>
                    </li>
                    <li>
                        <a href="/admin/screenings" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">📋</span> Llistat de projeccions
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Usuarios y roles -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Usuaris i Rols</h3>
            </div>
            <div style="padding: 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="/admin/users/create" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">➕</span> Nou usuari
                        </a>
                    </li>
                    <li style="margin-bottom: 12px;">
                        <a href="/admin/users" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">👤</span> Gestió d'usuaris
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Entradas y ventas -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Entrades i Vendes</h3>
            </div>
            <div style="padding: 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 12px;">
                        <a href="/admin/vendes" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">💰</span> Registre de vendes
                        </a>
                    </li>
                    <li>
                        <a href="/admin/informes" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">📊</span> Informes
                        </a>
                    </li>
                </ul>
            </div>
        </div>



        <!-- Configuración -->
        <div style="background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <div style="background-color: #800040; color: white; padding: 15px 20px;">
                <h3 style="font-size: 18px; margin: 0;">Configuració</h3>
            </div>
            <div style="padding: 20px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li>
                        <a href="/admin/configuracio" style="display: flex; align-items: center; text-decoration: none; color: #800040; font-weight: 500; padding: 10px; border-radius: 5px; background-color: rgba(128, 0, 64, 0.05);">
                            <span style="margin-right: 10px; font-size: 20px;">⚙️</span> Paràmetres generals
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection