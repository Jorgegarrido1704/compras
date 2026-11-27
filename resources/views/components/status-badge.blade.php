@php
    $config = [
        'solicitada' => ['label' => 'Solicitada', 'tone' => 'neutral'],
        'en_cotizacion' => ['label' => 'En cotización', 'tone' => 'pending'],
        'pendiente_autorizacion' => ['label' => 'Pend. autorización', 'tone' => 'pending'],
        'autorizada' => ['label' => 'Autorizada', 'tone' => 'approved'],
        'rechazada' => ['label' => 'Rechazada', 'tone' => 'rejected'],
        'pendiente_pago' => ['label' => 'Pend. de pago', 'tone' => 'pending'],
        'pagada' => ['label' => 'Pagada', 'tone' => 'approved'],
        'cancelada' => ['label' => 'Cancelada', 'tone' => 'neutral'],
    ][$estatus] ?? ['label' => $estatus, 'tone' => 'neutral'];

    $toneClasses = [
        'pending' => 'text-stamp-pending border-stamp-pending',
        'approved' => 'text-stamp-approved border-stamp-approved',
        'rejected' => 'text-stamp-rejected border-stamp-rejected',
        'neutral' => 'text-stamp-neutral border-stamp-neutral',
    ][$config['tone']];
@endphp

<span class="folio-stamp text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-1.5 border px-2 py-1 rounded-sm {{ $toneClasses }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
    {{ $config['label'] }}
</span>
