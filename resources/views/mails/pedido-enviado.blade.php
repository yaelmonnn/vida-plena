<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tu pedido está en camino</title>
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:'Lato', Arial, sans-serif; }
  .wrap { max-width:600px; margin:32px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,.08); }
  .header { background:#e05c3a; padding:32px 40px; text-align:center; }
  .header img { height:48px; }
  .header h1 { color:#fff; font-size:22px; font-weight:800; margin:16px 0 4px; }
  .header p  { color:rgba(255,255,255,.85); font-size:14px; margin:0; }
  .body { padding:32px 40px; }
  .greeting { font-size:16px; color:#374151; margin-bottom:8px; }
  .msg      { font-size:14px; color:#6b7280; line-height:1.6; margin-bottom:24px; }
  .info-box { background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:20px 24px; margin-bottom:24px; }
  .info-box .row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f3f4f6; font-size:13px; }
  .info-box .row:last-child { border-bottom:none; }
  .info-box .label { color:#9ca3af; font-weight:600; }
  .info-box .value { color:#374151; font-weight:700; text-align:right; max-width:60%; }
  .section-title { font-size:12px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:.06em; margin-bottom:12px; }
  .producto { display:flex; justify-content:space-between; align-items:flex-start; padding:12px 0; border-bottom:1px solid #f3f4f6; }
  .producto:last-child { border-bottom:none; }
  .prod-nombre { font-size:14px; font-weight:700; color:#374151; }
  .prod-sub    { font-size:12px; color:#9ca3af; margin-top:2px; }
  .prod-total  { font-size:14px; font-weight:800; color:#e05c3a; white-space:nowrap; }
  .total-row   { display:flex; justify-content:flex-end; align-items:center; gap:12px; border-top:2px solid #e5e7eb; padding-top:16px; margin-top:8px; }
  .total-label { font-size:13px; color:#6b7280; font-weight:600; }
  .total-val   { font-size:22px; font-weight:900; color:#e05c3a; }
  .badge { display:inline-block; background:#dbeafe; color:#1d4ed8; font-size:12px; font-weight:700; padding:4px 12px; border-radius:999px; }
  .footer { background:#f9fafb; border-top:1px solid #e5e7eb; padding:24px 40px; text-align:center; }
  .footer p  { font-size:12px; color:#9ca3af; margin:4px 0; }
</style>
</head>
<body>
<div class="wrap">

  {{-- Header --}}
  <div class="header">
    <h1>🚚 ¡Tu pedido está en camino!</h1>
    <p>Pedido #{{ $pedido->Id }} · Vida Plena</p>
  </div>

  {{-- Body --}}
  <div class="body">

    <p class="greeting">Hola, <strong>{{ $pedido->nombre_envio }}</strong></p>
    <p class="msg">
      ¡Buenas noticias! Tu pedido acaba de ser despachado y pronto llegará a tu puerta.
      A continuación encontrarás el resumen de tu compra.
    </p>

    {{-- Info envío --}}
    <p class="section-title">Información del envío</p>
    <div class="info-box">
      <div class="row">
        <span class="label">Estado</span>
        <span class="value"><span class="badge">Enviado</span></span>
      </div>
      <div class="row">
        <span class="label">Dirección</span>
        <span class="value">
          {{ implode(', ', array_filter([$pedido->calle_envio, $pedido->colonia_envio, $pedido->ciudad_envio, $pedido->cp_envio])) ?: '—' }}
        </span>
      </div>
      <div class="row">
        <span class="label">Correo</span>
        <span class="value">{{ $pedido->email_envio }}</span>
      </div>
      @if ($pedido->telefono_envio)
      <div class="row">
        <span class="label">Teléfono</span>
        <span class="value">{{ $pedido->telefono_envio }}</span>
      </div>
      @endif
    </div>

    {{-- Productos --}}
    <p class="section-title">Resumen de tu pedido</p>
    @foreach ($pedido->detalles as $d)
    <div class="producto">
      <div>
        <p class="prod-nombre">{{ $d->nombre_producto }}</p>
        <p class="prod-sub">
          {{ $d->cantidad }} × ${{ number_format($d->precio_unitario, 2) }}
          @if ($d->fecha_servicio)
            &nbsp;·&nbsp; 📅 {{ $d->fecha_servicio }}
          @endif
        </p>
      </div>
      <p class="prod-total">${{ number_format($d->subtotal, 2) }}</p>
    </div>
    @endforeach

    <div class="total-row">
      <span class="total-label">Total pagado</span>
      <span class="total-val">${{ number_format($pedido->total, 2) }}</span>
    </div>

    <p class="msg" style="margin-top:24px;">
      Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.<br>
      ¡Gracias por confiar en <strong>Vida Plena</strong>! 🌿
    </p>

  </div>

  {{-- Footer --}}
  <div class="footer">
    <p>© {{ date('Y') }} Vida Plena. Todos los derechos reservados.</p>
    <p>Este correo fue enviado a {{ $pedido->email_envio }}</p>
  </div>

</div>
</body>
</html>
