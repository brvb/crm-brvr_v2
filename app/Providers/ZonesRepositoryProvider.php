<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\Tenant\Setup\Zones\ZonesInterface;
use App\Repositories\Tenant\Setup\Zones\ZonesRepository;
use App\Repositories\Tenant\Setup\Zones\EloquentZonesRepository;

class ZonesRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        ZonesInterface::class => ZonesRepository::class
    ];
}
