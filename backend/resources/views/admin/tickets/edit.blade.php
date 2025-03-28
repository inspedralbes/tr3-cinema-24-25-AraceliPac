@extends('layouts.admin')

@section('title', 'Editar Entrada')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 style="color: #333; margin-bottom: 20px;">Editar Entrada #{{ $ticket->ticket_number }}</h2>
        </div>
    </div>

    @if ($errors->any())
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 12px 20px; border: 1px solid #f5c6cb; border-radius: 8px; margin-bottom: 20px; position: relative;">
        {{ session('error') }}
        <button type="button" style="position: absolute; top: 0; right: 0; padding: 12px 20px; background: none; border: none; font-size: 20px; font-weight: bold; cursor: pointer; color: #721c24;" onclick="this.parentElement.style.display='none'">&times;</button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-top: 3px solid #D4AF37;">
                <form action="{{ url('admin/tickets/' . $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 20px;">
                        <label for="ticket_number" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Número d'entrada</label>
                        <input type="text" id="ticket_number" name="ticket_number" value="{{ old('ticket_number', $ticket->ticket_number) }}" required
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('ticket_number') border-color: #dc3545; @enderror">
                        <small style="display: block; color: #6c757d; margin-top: 5px;">Format recomanat: AAAAMMDD-XXX on XXX és un número seqüencial</small>
                        @error('ticket_number')
                        <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="user_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Usuari</label>
                            <select id="user_id" name="user_id" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('user_id') border-color: #dc3545; @enderror">
                                <option value="">Selecciona un usuari</option>
                                @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $ticket->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="flex: 1; min-width: 200px;">
                            <label for="screening_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Projecció</label>
                            <select id="screening_id" name="screening_id" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('screening_id') border-color: #dc3545; @enderror">
                                <option value="">Selecciona una projecció</option>
                                @foreach($screenings ?? [] as $screening)
                                <option value="{{ $screening->id }}" {{ old('screening_id', $ticket->screening_id) == $screening->id ? 'selected' : '' }}>
                                    {{ $screening->movie->title ?? 'N/A' }} - {{ date('d/m/Y', strtotime($screening->screening_date)) }} {{ date('H:i', strtotime($screening->screening_time)) }}
                                </option>
                                @endforeach
                            </select>
                            @error('screening_id')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
                        <div style="flex: 1; min-width: 200px;">
                            <label for="seat_id" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Seient</label>
                            <select id="seat_id" name="seat_id" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('seat_id') border-color: #dc3545; @enderror">
                                <option value="">Selecciona un seient</option>
                                @foreach($seats ?? [] as $seat)
                                <option value="{{ $seat->id }}" {{ old('seat_id', $ticket->seat_id) == $seat->id ? 'selected' : '' }}>
                                    {{ $seat->seat_number }} (Fila: {{ $seat->row }}, Columna: {{ $seat->number }})
                                </option>
                                @endforeach
                            </select>
                            @error('seat_id')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div style="flex: 1; min-width: 200px;">
                            <label for="price" style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">Preu (€)</label>
                            <input type="number" id="price" name="price" value="{{ old('price', $ticket->price) }}" step="0.01" min="0" required
                                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; @error('price') border-color: #dc3545; @enderror">
                            @error('price')
                            <div style="color: #dc3545; font-size: 0.875em; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-start; padding-top: 15px; border-top: a solid #eee; margin-top: 30px;">
                        <button type="submit" style="background-color: #800040; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; margin-right: 10px; font-size: 14px;">
                            Guardar Canvis
                        </button>
                        <a href="{{ url('admin/tickets') }}" style="background-color: #6c757d; color: #FFFFFF; border: none; padding: 10px 16px; border-radius: 8px; text-decoration: none; display: inline-block; font-size: 14px;">Cancel·lar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection