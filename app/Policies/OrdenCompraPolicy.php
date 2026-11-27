<?php

namespace App\Policies;

use App\Models\OrdenCompra;
use App\Models\User;

class OrdenCompraPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['compras', 'gerencia', 'contabilidad', 'admin']);
    }

    public function view(User $user, OrdenCompra $orden): bool
    {
        if ($user->hasAnyRole(['compras', 'gerencia', 'contabilidad', 'admin'])) {
            return true;
        }

        return $orden->requisicion?->solicitante_id === $user->id;
    }

    public function autorizar(User $user, OrdenCompra $orden): bool
    {
        return ($user->hasRole('gerencia') || $user->hasRole('admin'))
            && $orden->estatus === 'pendiente_autorizacion';
    }

    public function marcarPagada(User $user, OrdenCompra $orden): bool
    {
        return ($user->hasRole('contabilidad') || $user->hasRole('admin'))
            && $orden->estatus === 'pendiente_pago';
    }
}
