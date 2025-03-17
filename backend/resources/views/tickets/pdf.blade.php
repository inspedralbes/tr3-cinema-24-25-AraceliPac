<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Entrada {{ $ticket->ticket_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .ticket {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #800040;
            border-radius: 8px;
            overflow: hidden;
        }
        .ticket-header {
            background-color: #800040;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .ticket-content {
            padding: 20px;
        }
        .movie-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .ticket-details {
            margin-top: 20px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .detail-label {
            font-weight: bold;
            width: 150px;
        }
        .detail-value {
            flex: 1;
        }
        .ticket-footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #ddd;
        }
        .qr-container {
            text-align: center;
            margin: 20px 0;
        }
        .seat-vip {
            background-color: #D4AF37;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>Cinema Pedralbes</h1>
            <h2>Entrada {{ $ticket->ticket_number }}</h2>
        </div>
        
        <div class="ticket-content">
            <div class="movie-title">{{ $ticket->screening->movie->title }}</div>
            
            <div class="ticket-details">
                <div class="detail-row">
                    <div class="detail-label">Data:</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($ticket->screening->date)->format('d/m/Y') }}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Hora:</div>
                    <div class="detail-value">{{ substr($ticket->screening->time, 0, 5) }}</div>
                </div>
                

                
                <div class="detail-row">
                    <div class="detail-label">Butaca:</div>
                    <div class="detail-value">
                        {{ $ticket->seat->row }}{{ $ticket->seat->number }}
                        @if($ticket->seat->is_vip)
                            <span class="seat-vip">VIP</span>
                        @endif
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Preu:</div>
                    <div class="detail-value">{{ number_format($ticket->price, 2) }} €</div>
                </div>
            </div>
            
            <div class="qr-container">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="Código QR">
                <p>Escaneja aquest codi per accedir a la sala</p>
            </div>
        </div>
        
        <div class="ticket-footer">
            <p>Gràcies per la seva compra. Conservi aquesta entrada fins al final de la projecció.</p>
            <p>Cinema Pedralbes - Tel: 93 XXX XX XX - www.cinemapedralbes.com</p>
        </div>
    </div>
</body>
</html>