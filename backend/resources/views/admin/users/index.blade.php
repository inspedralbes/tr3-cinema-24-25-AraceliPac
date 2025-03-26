@extends('layouts.admin')

@section('title', 'Gestió d\'Usuaris')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Llistat d'Usuaris</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.users.create') }}" style="display: inline-block; margin-left: 50px; background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; cursor: pointer; font-size: 14px;">
                <i class="fas fa-user-plus me-2"></i>Nou Usuari
            </a>
        </div>
    </div>

    <!-- Missatges d'èxit o error -->
    @if(session('success'))
    <div style="background-color: #d4edda; margin-top: 50px; color: #155724; padding: 12px 20px; border: 1px solid #c3e6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('success') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #155724;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div style="background-color: #f8d7da;margin-top: 50px;  color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('error') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #721c24;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    <!-- Filtres -->
    <div style="background-color: #fff;margin-top: 50px; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h5 style="color: #800040; margin-bottom: 15px; font-weight: bold;">Filtres</h5>
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nom</label>
                    <input type="text" name="name" value="{{ request('name') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email</label>
                    <input type="text" name="email" value="{{ request('email') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Rol</label>
                    <select name="role_id" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;">
                        <option value="">Tots els rols</option>
                        @foreach($roles ?? [] as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 0.5; min-width: 100px; display: flex; gap: 10px;">
                    <button type="submit" style="background-color: #800040; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px;">Filtrar</button>
                    <a href="{{ route('admin.users.index') }}" style="background-color: #6c757d; color: #FFFFFF; padding: 10px 16px; border-radius: 8px; text-decoration: none; border: none; display: inline-block; font-size: 14px;">Netejar</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Taula d'usuaris -->
    <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="overflow-x: auto;">
            <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">ID</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Imatge</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Nom</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Cognoms</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">Email</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Telèfon</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Rol</th>
                        <th style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users ?? [] as $user)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $user->id }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            @if($user->image)
                            <img src="{{ $user->image }}" alt="{{ $user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            @else
                            <div style="width: 40px; height: 40px; background-color: #800040; color: #FFFFFF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 16px;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            @endif
                        </td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">{{ $user->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">{{ $user->last_name }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: left;">{{ $user->email }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">{{ $user->phone ?? 'N/A' }}</td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            @if($user->role)
                            @if($user->role->id == 1)
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; background-color: #800040; color: white;">
                                {{ $user->role->name }}
                            </span>
                            @elseif($user->role->id == 2)
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; background-color: #D4AF37; color: white;">
                                {{ $user->role->name }}
                            </span>
                            @else
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; background-color: #6c757d; color: white;">
                                {{ $user->role->name }}
                            </span>
                            @endif
                            @else
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; background-color: #6c757d; color: white;">
                                Sense rol
                            </span>
                            @endif
                        </td>
                        <td style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">
                            <div style="display: flex; flex-wrap: wrap; gap: 5px; justify-content: center;">
                                <a href="{{ route('admin.users.show', $user->id) }}" style="background-color: #D4AF37; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Veure">
                                    Veure
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" style="background-color: #800040; color: #FFFFFF; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 14px; line-height: 1.5;" title="Editar">
                                    Editar
                                </a>
                                <button type="button" style="background-color: #dc3545; color: white; padding: 8px 14px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; line-height: 1.5;" title="Eliminar"
                                    onclick="if(confirm('Estàs segur que vols eliminar aquest usuari?')) { 
                                                document.getElementById('delete-form-{{ $user->id }}').submit(); 
                                            }">
                                    Eliminar
                                </button>
                                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="border: 1px solid #ddd; padding: 12px 8px; text-align: center;">No s'han trobat usuaris</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginació -->
        @if(isset($users) && method_exists($users, 'links'))
        <div style="margin-top: 20px; text-align: center;">
            <div style="display: inline-flex; background-color: white; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                @if($users->onFirstPage())
                <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa; border-right: 1px solid #ddd;">&laquo; Anterior</span>
                @else
                <a href="{{ $users->previousPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">&laquo; Anterior</a>
                @endif

                @php
                $currentPage = $users->currentPage();
                $lastPage = $users->lastPage();
                $range = 2; // Mostrar 2 páginas antes y después de la actual
                @endphp

                @for($i = 1; $i <= $lastPage; $i++)
                    @if($i==1 || $i==$lastPage || ($i>= $currentPage - $range && $i <= $currentPage + $range))
                        @if($i==$currentPage)
                        <span style="display: inline-block; padding: 10px 16px; background-color: #800040; color: white; border-right: 1px solid #ddd;">{{ $i }}</span>
                        @else
                        <a href="{{ $users->url($i) }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none; border-right: 1px solid #ddd;">{{ $i }}</a>
                        @endif
                        @elseif($i == $currentPage - $range - 1 || $i == $currentPage + $range + 1)
                        <span style="display: inline-block; padding: 10px 16px; border-right: 1px solid #ddd;">...</span>
                        @endif
                        @endfor

                        @if($currentPage == $lastPage)
                        <span style="display: inline-block; padding: 10px 16px; color: #6c757d; background-color: #f8f9fa;">Següent &raquo;</span>
                        @else
                        <a href="{{ $users->nextPageUrl() }}" style="display: inline-block; padding: 10px 16px; color: #800040; text-decoration: none;">Següent &raquo;</a>
                        @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection