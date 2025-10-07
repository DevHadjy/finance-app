<?php


namespace App\Policies;

use App\Models\Despesa;
use App\Models\User;

class DespesaPolicy
{
    public function update(User $user, Despesa $despesa)
    {
        return $user->is_admin || $despesa->user_id === $user->id;
    }

    public function delete(User $user, Despesa $despesa)
    {
        return $user->is_admin || $despesa->user_id === $user->id;
    }
}