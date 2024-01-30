<?php

namespace Xetreon\RolePermission\Core;
use App\Exceptions\AuthException;
use App\Models\User;
use App\Helpers\Auth;

class JwtAuthDriver
{
    /**
     * @return User|null
     * @throws AuthException
     */
    public function getAuth(): ?User
    {
        /** @var User $user */
        $user = Auth::getAuth();
        return $user;
    }
}
