<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\Tenant\Setup\Brands\BrandsInterface;
use App\Repositories\Tenant\Setup\Brands\BrandsRepository;

class BrandsRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        BrandsInterface::class => BrandsRepository::class
    ];
}
