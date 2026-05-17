<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
</head>
<body style="font-family: 'Outfit', Arial; background:#f5f5f5; padding:20px;">

    <div style="max-width:600px; margin:auto; background:white; border-radius:12px; padding:30px; box-shadow:0 10px 25px rgba(0,0,0,0.05);">

        {{-- LOGO --}}
        <div style="text-align:center; margin-bottom:20px;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}"
                alt="Vida Plena"
                style="width:120px; height:auto;">
        </div>

        <h3 style="text-align:center; color:#333;">
            ¡Gracias por tu compra, {{ explode(' ', $pedido->nombre_envio)[0] }}!
        </h3>

        <p style="text-align:center; color:#666;">
            Tu pedido <strong>#{{ $pedido->Id }}</strong> fue confirmado y está siendo procesado.
        </p>

        {{-- DETALLES DEL PEDIDO --}}
        <table style="width:100%; border-collapse:collapse; margin:24px 0;">
            <thead>
                <tr style="background:#f9f3ee;">
                    <th style="text-align:left; padding:10px 12px; color:#555; font-size:13px;">Producto</th>
                    <th style="text-align:center; padding:10px 12px; color:#555; font-size:13px;">Cant.</th>
                    <th style="text-align:right; padding:10px 12px; color:#555; font-size:13px;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->detalles as $detalle)
                <tr style="border-bottom:1px solid #f0f0f0;">
                    <td style="padding:10px 12px; color:#333; font-size:14px;">
                        {{ $detalle->nombre_producto }}
                        @if($detalle->fecha_servicio)
                            <br><span style="font-size:12px; color:#999;">Fecha: {{ \Carbon\Carbon::parse($detalle->fecha_servicio)->format('d/m/Y') }}</span>
                        @endif
                    </td>
                    <td style="padding:10px 12px; color:#333; font-size:14px; text-align:center;">
                        {{ $detalle->cantidad }}
                    </td>
                    <td style="padding:10px 12px; color:#333; font-size:14px; text-align:right;">
                        ${{ number_format($detalle->subtotal, 2) }} MXN
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="padding:12px; text-align:right; font-weight:bold; color:#333;">Total:</td>
                    <td style="padding:12px; text-align:right; font-weight:bold; color:#E48F62; font-size:16px;">
                        ${{ number_format($pedido->total, 2) }} MXN
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- DIRECCIÓN DE ENVÍO --}}
        <div style="background:#f9f3ee; border-radius:8px; padding:16px; margin-bottom:24px;">
            <p style="margin:0 0 6px; font-weight:bold; color:#555; font-size:13px;">Dirección de envío</p>
            <p style="margin:0; color:#666; font-size:14px; line-height:1.6;">
                {{ $pedido->nombre_envio }}<br>
                {{ $pedido->calle_envio }}
                @if($pedido->colonia_envio), {{ $pedido->colonia_envio }}@endif<br>
                @if($pedido->ciudad_envio){{ $pedido->ciudad_envio }}@endif
                @if($pedido->cp_envio), CP {{ $pedido->cp_envio }}@endif
            </p>
        </div>

        <p style="font-size:12px; color:#999; text-align:center;">
            Si tienes alguna duda sobre tu pedido, contáctanos respondiendo este correo.
        </p>

    </div>

</body>
</html>
