<?php

namespace App\Services\User;

use Rakhiazfa\LaravelSarp\Service\ServiceInterface;

interface UserService extends ServiceInterface
{
    /**
     * @param array $credentials
     * 
     * @return mixed
     */
    public function login(array $credentials, bool $expectsJson = true): mixed;
}
