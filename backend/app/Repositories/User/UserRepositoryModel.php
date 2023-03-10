<?php

namespace App\Repositories\User;

use Rakhiazfa\LaravelSarp\Repository\RepositoryModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepositoryModel extends RepositoryModel implements UserRepository
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * 
     * @return User
     */
    public function new(array $attributes = []): User
    {
        return new User($attributes);
    }
}
