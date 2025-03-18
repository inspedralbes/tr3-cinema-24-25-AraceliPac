<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <title>Registre exitós</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #333333;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .header {
            background-color: #800040;
            color: #FFFFFF;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #D4AF37;
        }

        .header h1 {
            margin: 0;
            color: #D4AF37;
            font-size: 28px;
            letter-spacing: 1px;
        }

        .content {
            padding: 30px;
            background-color: #000000;
            color: #FFFFFF;
        }

        .content h2 {
            color: #D4AF37;
            margin-top: 0;
            font-size: 22px;
            border-bottom: 1px solid #D4AF37;
            padding-bottom: 10px;
        }

        .content p {
            line-height: 1.6;
            color: #FFFFFF;
        }

        .button {
            display: inline-block;
            background-color: #800040;
            color: #FFFFFF;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            border: 1px solid #D4AF37;
            font-weight: bold;
            text-align: center;
        }

        .button:hover {
            background-color: #9A0040;
        }

        .footer {
            padding: 15px;
            font-size: 0.9em;
            color: #D4AF37;
            text-align: center;
            background-color: #800040;
            border-top: 2px solid #D4AF37;
        }

        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            color: #D4AF37;
            margin: 0 10px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <!-- Aquí pots afegir el teu logotip -->
            <!-- <img src="logo.png" alt="Logo Cinema" class="logo"> -->
            <h1>¡Benvingut/da al {{ config('app.name') }}!</h1>
        </div>
        <div class="content">
            <h2>Hola, {{ $user->name }}!</h2>
            <p>El teu registre s'ha realitzat correctament. Gràcies per confiar en nosaltres.</p>
            <p>Ara pots gaudir de tots els avantatges exclusius de ser membre del nostre cinema:</p>
            <ul style="color: #FFFFFF; list-style-type: none; padding-left: 0;">
                <li style="margin-bottom: 10px; padding-left: 20px; position: relative;">
                    <span style="color: #D4AF37; position: absolute; left: 0;">✓</span> Entrades amb descompte
                </li>
                <li style="margin-bottom: 10px; padding-left: 20px; position: relative;">
                    <span style="color: #D4AF37; position: absolute; left: 0;">✓</span> Accés preferent a estrenes
                </li>
                <li style="margin-bottom: 10px; padding-left: 20px; position: relative;">
                    <span style="color: #D4AF37; position: absolute; left: 0;">✓</span> Promocions exclusives en menjar i begudes
                </li>
            </ul>
            <p>Pots començar a explorar la nostra cartellera actual i comprar entrades online:</p>
            <div style="text-align: center;">
                <a href="http://cinema.daw.inspedralbes.cat/pelicules/cartelera" class="button">VISITAR LA CARTELLERA</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Cinema {{ config('app.name') }}. Tots els drets reservats.</p>
            <div class="social-icons">
                <a href="#">Facebook</a> | <a href="#">Instagram</a> | <a href="#">Twitter</a>
            </div>
        </div>
    </div>
</body>

</html>