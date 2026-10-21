<?php

namespace App\Repositories\Contracts;

use App\Repositories\Eloquent\UserRepository;
use Illuminate\Container\Attributes\Bind;

#[Bind(UserRepository::class)]
interface UserRepositoryInterface extends BaseRepositoryInterface {}
