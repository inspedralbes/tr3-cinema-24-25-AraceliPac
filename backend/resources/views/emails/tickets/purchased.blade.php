<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            background-color: #800040;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
        }

        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
        }

        .ticket-info {
            background-color: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Cinema Pedralbes</h1>
            <h2>Confirmació de tickets</h2>
        </div>

        <div class="content">
            <p>Hola {{ $ticket->user->name }} {{ $ticket->user->last_name }},</p>

            <p>¡Gràcies per la teva compra! La teva entrada està confirmada.</p>

            <div class="ticket-info">
                <h3>{{ $ticket->screening->movie->title }}</h3>

                <!-- Fecha y hora de la compra (cuando se creó el ticket) -->
                <p><strong>Data comanda:</strong> {{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y') }}</p>
                <p><strong>Hora comanda:</strong> {{ \Carbon\Carbon::parse($ticket->created_at)->format('H:i') }}</p>

                <!-- Fecha y hora de la sesión de cine (screening) -->
                <p><strong>Sessió:</strong> {{ \Carbon\Carbon::parse($ticket->screening->screening_date)->format('d/m/Y') }} a les {{ \Carbon\Carbon::parse($ticket->screening->screening_time)->format('H:i') }}</p>

                <p><strong>Butaca:</strong> {{ $ticket->seat->row }}{{ $ticket->seat->number }}
                    @if($ticket->seat->is_vip)
                    <span style="background-color:#D4AF37; color:white; padding:2px 5px; border-radius:3px; font-size:12px;">VIP</span>
                    @endif
                </p>
                <p><strong>Número de Ticket:</strong> {{ $ticket->ticket_number }}</p>
            </div>

            <p>Pot accedir a la teva entrada des de el teu perfil en la nostra web.</p>
        </div>

        <div class="footer">
            <p>Cinema Pedralbes - El millor cine de Barcelona</p>
            <p>Si tens preguntes, contacta amb nostraltres en info@cinemapedralbes.com</p>
        </div>
    </div>
</body>

</html>