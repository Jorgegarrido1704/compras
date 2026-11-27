<?php

namespace App\Policies;

use App\Models\Requisicion;
use App\Models\User;

class RequisicionPolicy
{
    /**
     * Quien puede ver el listado general (el controlador filtra el contenido según rol).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['solicitante', 'compras', 'gerencia', 'contabilidad', 'admin']);
    }

    public function view(User $user, Requisicion $requisicion): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('solicitante')) {
            return $requisicion->solicitante_id === $user->id;
        }

        return $user->hasAnyRole(['compras', 'gerencia', 'contabilidad']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Requisicion $requisicion): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('solicitante')) {
            return $requisicion->solicitante_id === $user->id
                && $requisicion->estatus === 'solicitada';
        }

        return false;
    }

    public function delete(User $user, Requisicion $requisicion): bool
    {
        return $user->hasRole('admin')
            || ($user->hasRole('solicitante')
                && $requisicion->solicitante_id === $user->id
                && $requisicion->estatus === 'solicitada');
    }
}
