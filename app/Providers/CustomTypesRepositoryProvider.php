<?php

namespace App\Providers;

use App\Interfaces\Tenant\Setup\CustomTypes\CustomTypesInterface;
use Illuminate\Support\ServiceProvider;

use App\Repositories\Tenant\Setup\CustomTypes\CustomTypesRepository;


class CustomTypesRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        CustomTypesInterface::class => CustomTypesRepository::class
    ];
}
