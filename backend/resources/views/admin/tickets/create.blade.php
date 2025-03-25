@extends('layouts.admin')

@section('title', 'Crear Nova Entrada')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="form-title">Afegir Nova Entrada</h2>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert-error">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="form-container">
                <form action="{{ route('admin.tickets.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="ticket_number">Número d'entrada</label>
                        <input type="text" id="ticket_number" name="ticket_number" value="{{ old('ticket_number') }}" required>
                        <small>Format recomanat: AAAAMMDD-XXX on XXX és un número seqüencial</small>
                        @error('ticket_number')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="user_id">Usuari</label>
                            <select id="user_id" name="user_id" required>
                                <option value="">Selecciona un usuari</option>
                                @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="screening_id">Projecció</label>
                            <select id="screening_id" name="screening_id" required>
                                <option value="">Selecciona una projecció</option>
                                @foreach($screenings ?? [] as $screening)
                                <option value="{{ $screening->id }}">
                                    {{ $screening->movie->title ?? 'N/A' }} - {{ date('d/m/Y H:i', strtotime($screening->screening_date . ' ' . $screening->screening_time)) }}
                                </option>
                                @endforeach
                            </select>
                            @error('screening_id')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="seat_id">Seient</label>
                            <select id="seat_id" name="seat_id" required>
                                <option value="">Selecciona un seient</option>
                                @foreach($seats ?? [] as $seat)
                                <option value="{{ $seat->id }}" data-screening="{{ $seat->screening_id }}">
                                    {{ $seat->row }}{{ $seat->number }}
                                </option>
                                @endforeach
                            </select>
                            @error('seat_id')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="price">Preu (€)</label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                            @error('price')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar Entrada</button>
                        <a href="{{ url('admin/tickets') }}" class="btn-secondary">Cancel·lar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('screening_id').addEventListener('change', function() {
        let screeningId = this.value;
        let seatSelect = document.getElementById('seat_id');

        Array.from(seatSelect.options).forEach(option => {
            option.style.display = (option.getAttribute('data-screening') == screeningId || option.value == '') ? 'block' : 'none';
        });

        if (seatSelect.querySelector('option:checked')?.style.display === 'none') {
            seatSelect.value = '';
        }
    });
</script>

<style>
    /* Globales */
    .form-title {
        color: #333;
        margin-bottom: 20px;
        font-size: 22px;
        font-weight: bold;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 12px 20px;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 20px;
    }

    .form-container {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-top: 3px solid #D4AF37;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-group small {
        display: block;
        color: #6c757d;
        margin-top: 5px;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        margin-top: 30px;
    }

    .btn-primary {
        background-color: #800040;
        color: #FFFFFF;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #FFFFFF;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
    }
</style>
@endsection