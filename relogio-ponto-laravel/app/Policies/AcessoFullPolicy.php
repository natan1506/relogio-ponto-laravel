<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcessoFullPolicy
{
    use HandlesAuthorization;

    public function full(User $user)
    {
        return auth()->user()->acesso == "FULL";
    }
}






