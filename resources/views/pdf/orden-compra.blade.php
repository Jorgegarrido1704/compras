<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra {{ $orden->folio }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; margin: 0; }
        .folio { font-size: 14px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { padding: 6px 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .label { color: #666; font-size: 10px; text-transform: uppercase; }
        .total { font-size: 16px; font-weight: bold; text-align: right; margin-top: 15px; }
        .footer { margin-top: 40px; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orden de Compra</h1>
        <div class="folio">{{ $orden->folio }}</div>
    </div>
    <table>
        <tr>
            <td>
                <div class="label">Solicitante</div>
                {{ $orden->requisicion->solicitante->name }}
            </td>
            <td>
                <div class="label">Fecha de autorización</div>
                {{ optional($orden->fecha_autorizacion)->format('d/m/Y') }}
            </td>
            <td>
                <div class="label">Fecha de pago programada</div>
                {{ optional($orden->fecha_pago_programada)->format('d/m/Y') }}
            </td>
        </tr>
    </table>

    <div style="margin-top: 20px; border: 1px solid #ddd; padding: 12px; background: #fafafa;">
        <div class="label" style="margin-bottom: 8px;">Datos del proveedor</div>
        <table>
            <tr>
                <td style="width: 50%;">
                    <div class="label">Razón social</div>
                    {{ $orden->cotizacion->proveedor->nombre }}
                </td>
                <td>
                    <div class="label">RFC</div>
                    {{ $orden->cotizacion->proveedor->rfc ?? 'No registrado' }}
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label">Contacto</div>
                    {{ $orden->cotizacion->proveedor->contacto ?? 'No registrado' }}
                </td>
                <td>
                    <div class="label">Teléfono</div>
                    {{ $orden->cotizacion->proveedor->telefono ?? 'No registrado' }}
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label">Correo</div>
                    {{ $orden->cotizacion->proveedor->email ?? 'No registrado' }}
                </td>
                <td>
                    <div class="label">Dirección</div>
                    {{ $orden->cotizacion->proveedor->direccion ?? 'No registrada' }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <tr>
            <th>Concepto</th>
            <th>Monto</th>
        </tr>
        <tr>
            <td>{{ $orden->requisicion->titulo }} — {{ $orden->requisicion->descripcion }}</td>
            <td>{{ $orden->cotizacion->moneda }} ${{ number_format($orden->cotizacion->monto, 2) }}</td>
        </tr>
    </table>

    <div class="total">
        Total: {{ $orden->cotizacion->moneda }} ${{ number_format($orden->cotizacion->monto, 2) }}
    </div>

    <div class="footer">
        Documento generado automáticamente por el sistema de autorización de compras.
    </div>
</body>
</html>
