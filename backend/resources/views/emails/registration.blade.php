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
        
        .registration-info {
            background-color: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        
        .benefits {
            margin: 15px 0;
        }
        
        .benefits li {
            margin-bottom: 8px;
            list-style-type: none;
            padding-left: 20px;
            position: relative;
        }
        
        .benefits li:before {
            content: "✓";
            color: #800040;
            position: absolute;
            left: 0;
        }
        
        .button {
            display: inline-block;
            background-color: #800040;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Cinema Pedralbes</h1>
            <h2>Confirmació de registre</h2>
        </div>
        
        <div class="content">
            <p>Hola {{ $user->name }} {{ $user->last_name }},</p>
            
            <p>¡El teu registre s'ha realitzat correctament! Gràcies per confiar en nosaltres.</p>
            
            <div class="registration-info">
                <h3>Detalls del teu compte</h3>
                <p><strong>Nom d'usuari:</strong> {{ $user->name }}</p>
                <p><strong>Correu electrònic:</strong> {{ $user->email }}</p>
                <p><strong>Data de registre:</strong> {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</p>
            </div>
            
            <p>Ara pots gaudir de tots els avantatges exclusius de ser membre del nostre cinema:</p>
            
            <ul class="benefits">
                <li>Entrades amb descompte</li>
                <li>Accés preferent a estrenes</li>
                <li>Promocions exclusives en menjar i begudes</li>
            </ul>
            
            <p>Pots començar a explorar la nostra cartellera actual i comprar entrades online:</p>
            
            <div style="text-align: center;">
                <a href="http://cinema.daw.inspedralbes.cat/pelicules/cartelera" class="button">VISITAR LA CARTELLERA</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Cinema Pedralbes - El millor cine de Barcelona</p>
            <p>Si tens preguntes, contacta amb nostraltres en info@cinemapedralbes.com</p>
        </div>
    </div>
</body>
</html>