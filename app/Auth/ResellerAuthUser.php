<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class ResellerAuthUser implements Authenticatable
{
    private $user;

    public function __construct($user)
    {
        $this->user = (object) $user;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->user->id;
    }

    public function getAuthPassword()
    {
        return $this->user->password;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // Not needed
    }

    public function getRememberTokenName()
    {
        return null;
    }

    public function __get($key)
    {
        return $this->user->$key ?? null;
    }
}