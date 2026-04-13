<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificarCuentaMail;

class LoginUsuarioController extends Controller
{
    public function mostrarLogin(): View
    {
        return view('login.login-usuario');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }


        if (!$usuario->activo) {
            throw ValidationException::withMessages([
                'email' => 'Debes verificar tu correo antes de iniciar sesión.',
            ]);
        }


        Auth::guard('usuario')->login($usuario, $request->boolean('recuerdame'));

        $usuario->registrarAcceso();
        $request->session()->regenerate();

        return redirect()->intended(route('inicio'));
    }

    public function mostrarRegistro(): View
    {
        return view('login.registro-usuario');
    }

    public function sesionExpirada(Request $request): RedirectResponse
    {
        Auth::guard('usuario')->logout();
        $request->session()->forget([
            'usuario_id',
        ]);

        return redirect()->route('login.usuario')
            ->with('info', 'Tu sesión fue cerrada por inactividad.');
    }

    public function registrar(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre'   => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:180', 'unique:usuario,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento' => ['required', 'date', 'before:-18 years'],
            'calle'    => ['nullable', 'string'],
            'colonia'  => ['nullable', 'string'],
            'ciudad'   => ['nullable', 'string'],
            'estado_dir'=> ['nullable', 'string'],
            'cp'       => ['nullable', 'string'],
            'terminos' => ['accepted'],
        ], [
            'email.unique'       => 'Este correo ya está registrado.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'terminos.accepted'  => 'Debes aceptar los términos y condiciones.',
        ]);

        $usuario = Usuario::create([
            'nombre'   => $request->nombre,
            'apellido' => $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->password),

            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'calle' => $request->calle,
            'colonia' => $request->colonia,
            'ciudad' => $request->ciudad,
            'estado_dir' => $request->estado_dir,
            'cp' => $request->cp,

            'activo' => 0,
            'creado_en' => now(),
            'actualizado_en' => now(),
        ]);


        $token = JWTAuth::customClaims([
            'tipo' => 'verificacion'
        ])->fromUser($usuario);

        $link = url("/verificar-cuenta?token=$token");

        Mail::to($usuario->email)->send(
            new VerificarCuentaMail($usuario, $link)
        );

        return redirect()->route('login.usuario')
            ->with('success', 'Revisa tu correo para verificar tu cuenta');
    }

    public function verificarCuenta(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);

        try {
            $payload = JWTAuth::setToken($request->token)->getPayload();

            // Validar tipo
            if ($payload->get('tipo') !== 'verificacion') {
                throw new \Exception('Token inválido');
            }

            // Obtener ID del usuario
            $userId = $payload->get('sub');

            $usuario = Usuario::findOrFail($userId);

            // Activar cuenta
            $usuario->activo = 1;
            $usuario->email_verified_at = now();
            $usuario->save();

            // Login manual (SIN JWT)
            Auth::guard('usuario')->login($usuario);
            $request->session()->regenerate();

            return redirect()->route('inicio');

        } catch (\Exception $e) {
            return redirect()->route('login.usuario')
                ->withErrors('Token inválido o expirado');
        }
    }
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('usuario')->logout();
        $request->session()->forget([
            'usuario_id', // o las claves que uses para usuario
        ]);
        return redirect()->route('inicio');
    }
}
