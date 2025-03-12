<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Comprovant de Registre</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            background-color: #000000; 
            color: #FFFFFF; 
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #D4AF37;
            border-radius: 8px;
            overflow: hidden;
        }
        .header { 
            background-color: #800040; 
            text-align: center; 
            padding: 20px; 
            border-bottom: 3px solid #D4AF37;
        }
        .header h1 { 
            color: #D4AF37; 
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .subheader {
            background-color: #333333;
            padding: 10px 20px;
            color: #D4AF37;
            font-style: italic;
            text-align: center;
        }
        .content { 
            padding: 30px; 
            background-color: #000000;
        }
        .info { 
            margin-bottom: 15px; 
            padding: 10px;
            border-bottom: 1px solid #800040;
        }
        .info-label {
            color: #D4AF37;
            font-weight: bold;
            width: 180px;
            display: inline-block;
        }
        .info-value {
            color: #FFFFFF;
        }
        .qr-section {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            border: 1px dashed #D4AF37;
        }
        .qr-placeholder {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000;
            font-size: 10px;
            text-align: center;
        }
        .footer {
            background-color: #800040;
            padding: 15px;
            text-align: center;
            color: #D4AF37;
            font-size: 12px;
            border-top: 2px solid #D4AF37;
        }
        .benefits {
            margin-top: 30px;
            padding: 15px;
            background-color: #333333;
            border-radius: 5px;
        }
        .benefits h3 {
            color: #D4AF37;
            margin-top: 0;
            text-align: center;
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
            Cinema {{ config('app.name') }} - La millor experiència cinematogràfica
        </div>
        
        <div class="content">
            <div class="info">
                <span class="info-label">Nom:</span>
                <span class="info-value">{{ $user->name }}</span>
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
</html>