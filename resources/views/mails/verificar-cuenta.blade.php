<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifica tu cuenta</title>
</head>
<body style="font-family: 'Outfit', Arial; background:#f5f5f5; padding:20px;">

    <div style="max-width:600px; margin:auto; background:white; border-radius:12px; padding:30px; box-shadow:0 10px 25px rgba(0,0,0,0.05);">

        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom:20px;">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Vida Plena"
                 style="width:120px; height:auto;">
        </div>

        <h3 style="text-align:center; color:#333;">
            Hola {{ $usuario->nombre }}
        </h3>

        <p style="text-align:center; color:#666;">
            Gracias por registrarte. Solo falta un paso:
        </p>

        <div style="text-align:center; margin:30px 0;">
            <a href="{{ $link }}"
               style="background:#E48F62; color:white; padding:14px 24px; border-radius:8px; text-decoration:none; font-weight:bold; display:inline-block;">
                Verificar mi cuenta
            </a>
        </div>

        <p style="font-size:12px; color:#999; text-align:center;">
            Si no creaste esta cuenta, puedes ignorar este mensaje.
        </p>

    </div>

</body>
</html>
