<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Comprovant de Registre</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #800040;
            border-radius: 8px;
            overflow: hidden;
        }
        .header { 
            background-color: #800040; 
            text-align: center; 
            padding: 20px; 
            border-bottom: 2px solid #800040;
            color: white;
        }
        .header h1 { 
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .subheader {
            background-color: #f9f9f9;
            padding: 10px 20px;
            color: #800040;
            font-style: italic;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        .content { 
            padding: 30px; 
            background-color: #ffffff;
        }
        .info { 
            margin-bottom: 15px; 
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            color: #800040;
            font-weight: bold;
            width: 180px;
            display: inline-block;
        }
        .info-value {
            color: #333;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
        }
        .benefits {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .benefits h3 {
            color: #800040;
            margin-top: 0;
            text-align: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .benefits ul {
            list-style-type: none;
            padding-left: 0;
        }
        .benefits li {
            padding-left: 20px;
            position: relative;
            margin-bottom: 8px;
        }
        .benefits li:before {
            content: "★";
            color: #D4AF37;
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>COMPROVANT DE REGISTRE</h1>
        </div>
        
        <div class="subheader">
             {{ config('app.name') }} - La millor experiència cinematogràfica
        </div>
        
        <div class="content">
            <div class="info">
                <span class="info-label">Nom:</span>
                <span class="info-value">{{ $user->name }} {{ $user->last_name }}</span>
            </div>
            
            <div class="info">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $user->email }}</span>
            </div>
            
            <div class="info">
                <span class="info-label">Data de registre:</span>
                <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="benefits">
                <h3>AVANTATGES DEL TEU REGISTRE</h3>
                <ul>
                    <li>Descomptes exclusius en totes les sessions</li>
                    <li>Promocions especials en menjar i begudes</li>
                    <li>Invitacions a preestrenes exclusives</li>
                    <li>Accés al nostre programa de fidelització</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p>Cinema {{ config('app.name') }} &copy; {{ date('Y') }}. Tots els drets reservats.</p>
            <p>Aquest document és personal i intransferible. Conserva'l per a futures referències.</p>
        </div>
    </div>
</body>