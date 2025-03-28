<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\registerMail;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Barryvdh\DomPDF\Facade\Pdf;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:255',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);

        // Comprobar si el usuario ya existe (opcional, ya lo valida unique)
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'El usuario ya está registrado.'], 400);
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role_id' => $request->role_id,
        ]);

        // Generar token de autenticación (si lo usas)
        $token = $user->createToken('authToken')->plainTextToken;

        // Renderizar la vista del correo
        $emailHtml = view('emails.registration', ['user' => $user])->render();

        // Generar el PDF usando la vista
        $pdf = Pdf::loadView('pdf.registration', ['user' => $user]);
        $pdfString = $pdf->output();

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
            $mail->Port       = env('MAIL_PORT', 587);

            // Configurar el remitente y destinatario
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($user->email, $user->name);

            // Configurar el correo
            $mail->isHTML(true);
            $mail->Subject = '¡Registro exitoso!';
            $mail->Body    = $emailHtml;

            // Adjuntar el PDF
            $mail->addStringAttachment($pdfString, 'comprobante_registro.pdf');

            // Enviar el correo
            $mail->send();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Usuario registrado, pero el correo no pudo enviarse.',
                'error'   => $mail->ErrorInfo,
            ], 500);
        }

        // Respuesta exitosa - incluir los datos del usuario
        return response()->json([
            'message' => 'Usuario registrado',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_id' => $user->role_id,
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Intentar encontrar al usuario
        $user = User::where('email', $request->email)->first();

        // Verificar que el usuario existe y que la contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Generar el token de autenticación
        $token = $user->createToken('authToken')->plainTextToken;


        return response()->json([
            'message' => 'Login correcto',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_id' => $user->role_id,
            ]
        ], 200);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role_id' => $user->role_id,
            ]);
        } else {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
