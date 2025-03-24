@extends('layouts.admin')
@section('content')
<div class="container">
    <h1>Panell d'Administració de Cinema</h1>

    <div class="row">
        <div class="col-md-4">
            <h2>Pel·lícules Actives</h2>
        </div>
        <div class="col-md-4">
            <h2>Entrades Venudes (Avui)</h2>
        </div>
        <div class="col-md-4">
            <h2>Projeccions Programades</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h2>Usuaris Registrats</h2>
        </div>
        <div class="col-md-4">
            <h2>Gestió de Pel·lícules</h2>
        </div>
        <div class="col-md-4">
            <h2>Projeccions i Sales</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h2>Entrades i Vendes</h2>
        </div>
        <div class="col-md-4">
            <h2>Usuaris i Rols</h2>
        </div>
        <div class="col-md-4">
            <h2>Configuració</h2>
        </div>
    </div>
</div>
@endsection