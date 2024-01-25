<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/foo', function () {
//     return view('welcome');
// })->middleware(['universal', InitializeTenancyByDomain::class]);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/createdemo/{domain}', function ($domain) {
    $tenant1 = App\Models\Tenant::create();

    $tenant1->domains()->create(['domain' => $domain, 'tenant_id' => $tenant1->id]);

    App\Models\Tenant::all()->runForEach(function () {//erro
        App\Models\User::factory()->create();
    });

    $storage_path = storage_path('storage' . $tenant1->id);

    // mkdir($storage_path);
    // mkdir("$storage_path/framework");
    // mkdir("$storage_path/framework/cache");
    // mkdir("$storage_path/framework/sessions");
    // mkdir("$storage_path/framework/testing");
    // mkdir("$storage_path/framework/views");
    // mkdir("$storage_path/logs");

    $storage_path = storage_path('tenants/' . $tenant1->id);

    mkdir($storage_path);
    mkdir("$storage_path/app");
    mkdir("$storage_path/app/public");
    mkdir("$storage_path/framework");
    mkdir("$storage_path/framework/cache");
    mkdir("$storage_path/framework/sessions");
    mkdir("$storage_path/framework/testing");
    mkdir("$storage_path/framework/views");
    mkdir("$storage_path/logs");

    $array = config('filesystems.links');

    $array[public_path('cl/' . $tenant1->id)] = $storage_path . '/app/public';
    config(['filesystems.links' => $array]);

    Artisan::call('storage:link');
    Artisan::call('tenants:seed --class=DistrictsSeeder');
    Artisan::call('tenants:seed --class=CountiesSeeder');
    Artisan::call('tenants:seed --class=ConfigSeeder');

});

Route::get('/createdemo/migrate', function ($domain) {
    Artisan::call('tenants:migrate');
    echo Artisan::output() . "<br>";
});

Route::get('/storageLink', function ($domain) {
    Artisan::call('storage:link');
});

require __DIR__.'/auth.php';
