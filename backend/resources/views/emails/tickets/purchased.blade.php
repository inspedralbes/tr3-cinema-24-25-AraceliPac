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
            <h2>Tu Entrada está Lista</h2>
        </div>
        
        <div class="content">
            <p>Hola {{ $ticket->user->name }},</p>
            
            <p>¡Gracias por tu compra! Tu entrada para la película está lista.</p>
            
            <div class="ticket-info">
                <h3>{{ $ticket->screening->movie->title }}</h3>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($ticket->screening->date)->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ substr($ticket->screening->time, 0, 5) }}</p>
                <p><strong>Sessio:</strong> Sala {{ $ticket->screening->id }}</p>
                <p><strong>Butaca:</strong> {{ $ticket->seat->row }}{{ $ticket->seat->number }}
                    @if($ticket->seat->is_vip)
                        <span style="background-color:#D4AF37; color:white; padding:2px 5px; border-radius:3px; font-size:12px;">VIP</span>
                    @endif
                </p>
                <p><strong>Número de Ticket:</strong> {{ $ticket->ticket_number }}</p>
            </div>
            
            <p>Puedes acceder a tu entrada desde tu perfil en nuestra web.</p>
        </div>
        
        <div class="footer">
            <p>Cinema Pedralbes - El mejor cine de Barcelona</p>
            <p>Si tienes preguntas, contáctanos en info@cinemapedralbes.com</p>
        </div>
    </div>
</body>
</html>