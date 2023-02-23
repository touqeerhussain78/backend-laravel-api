<?php

namespace App\Repositories;

use App\Core\BaseRepository;
use App\Contracts\Repositories\IUserRepository;
use App\Models\User;

class UserRepository  extends BaseRepository  implements IUserRepository {

    protected $entity;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }
}
