@extends('layouts.admin')

@section('title', 'Panell d\'Administració')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Pel·lícules Actives</h2>
                </div>
                <div class="card-body">
                    <p>5 pel·lícules en cartellera</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Entrades Venudes (Avui)</h2>
                </div>
                <div class="card-body">
                    <p>42 entrades</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Projeccions Programades</h2>
                </div>
                <div class="card-body">
                    <p>15 projeccions</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Usuaris Registrats</h2>
                </div>
                <div class="card-body">
                    <p>124 usuaris</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Gestió de Pel·lícules</h2>
                </div>
                <div class="card-body">
                    <ul>
                        <li><a href="/admin/movies/create">Nova pel·lícula</a></li>
                        <li><a href="/admin/movies">Llistat de pel·lícules</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Projeccions</h2>
                </div>
                <div class="card-body">
                    <ul>
                        <li><a href="/admin/screenings/create">Nova projecció</a></li>
                        <li><a href="/admin/screenings">Llistat de projeccions</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Entrades i Vendes</h2>
                </div>
                <div class="card-body">
                    <ul>
                        <li><a href="/admin/vendes">Registre de vendes</a></li>
                        <li><a href="/admin/informes">Informes</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Usuaris i Rols</h2>
                </div>
                <div class="card-body">
                    <ul>
                        <li><a href="/admin/usuaris">Gestió d'usuaris</a></li>
                        <li><a href="/admin/rols">Gestió de rols</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2>Configuració</h2>
                </div>
                <div class="card-body">
                    <ul>
                        <li><a href="/admin/configuracio">Paràmetres generals</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection