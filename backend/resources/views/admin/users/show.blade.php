@extends('layouts.admin')

@section('title', 'Detalls de l\'Usuari')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 style="color: #333; margin-bottom: 20px;">Detalls de l'Usuari</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-top: 3px solid #D4AF37;">

                <!-- Capçalera amb ID -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                    <h3 style="margin: 0; color: #333; font-weight: bold;"><i class="fas fa-user me-2" style="color: #800040;"></i>{{ $user->name }} {{ $user->last_name }}</h3>
                    <span style="background-color: #800040; color: white; padding: 5px 10px; border-radius: 8px; font-size: 0.9rem;">ID: {{ $user->id }}</span>
                </div>

                <!-- Informació de l'usuari -->
                <div style="display: flex; flex-wrap: wrap; gap: 30px; margin-bottom: 30px;">

                    <!-- Imatge de perfil -->
                    <div style="flex: 0 0 200px; text-align: center;">
                        @if($user->image)
                        <img src="{{ $user->image }}" alt="{{ $user->name }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #D4AF37;">
                        @else
                        <div style="width: 150px; height: 150px; background-color: #800040; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 4rem;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        @endif

                        @php
                        $roleColor = '#6c757d'; // Color por defecto (gris)
                        $roleName = 'Sense rol';

                        if ($user->role) {
                        $roleName = $user->role->name;
                        if ($user->role->id == 1) {
                        $roleColor = '#800040'; // Admin (granate)
                        } else if ($user->role->id == 2) {
                        $roleColor = '#D4AF37'; // Trabajador (dorado)
                        }
                        }
                        @endphp

                        <div style="margin-top: 15px;">
                            @if($user->role)
                            @if($user->role->id == 1)
                            <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #800040; color: white; font-weight: 500;">
                                {{ $user->role->name }}
                            </span>
                            @elseif($user->role->id == 2)
                            <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #D4AF37; color: white; font-weight: 500;">
                                {{ $user->role->name }}
                            </span>
                            @else
                            <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #6c757d; color: white; font-weight: 500;">
                                {{ $user->role->name }}
                            </span>
                            @endif
                            @else
                            <span style="display: inline-block; padding: 6px 15px; border-radius: 20px; background-color: #6c757d; color: white; font-weight: 500;">
                                Sense rol
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Detalls de l'usuari -->
                    <div style="flex: 1; min-width: 300px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #800040; width: 30%;">
                                    <i class="fas fa-envelope me-2"></i>Correu electrònic:
                                </td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee;">
                                    {{ $user->email }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #800040;">
                                    <i class="fas fa-phone me-2"></i>Telèfon:
                                </td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee;">
                                    {{ $user->phone ?? 'No especificat' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #800040;">
                                    <i class="fas fa-calendar-alt me-2"></i>Data de registre:
                                </td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee;">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #800040;">
                                    <i class="fas fa-clock me-2"></i>Última actualització:
                                </td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee;">
                                    {{ $user->updated_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @if($user->email_verified_at)
                            <tr>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #800040;">
                                    <i class="fas fa-check-circle me-2"></i>Email verificat:
                                </td>
                                <td style="padding: 12px 10px; border-bottom: 1px solid #eee;">
                                    {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Botons d'acció -->
                <div style="display: flex; flex-wrap: wrap; gap: 10px; padding-top: 15px; border-top: 1px solid #eee;">
                    <a href="{{ route('admin.users.edit', $user->id) }}" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('admin.users.index') }}" style="background-color: #6c757d; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">
                        <i class="fas fa-arrow-left me-2"></i>Tornar al Llistat
                    </a>
                    <button type="button" style="background-color: #dc3545; color: white; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; margin-left: auto;"
                        onclick="if(confirm('Estàs segur que vols eliminar aquest usuari?')) { document.getElementById('delete-form').submit(); }">
                        <i class="fas fa-trash-alt me-2"></i>Eliminar
                    </button>

                    <form id="delete-form" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection