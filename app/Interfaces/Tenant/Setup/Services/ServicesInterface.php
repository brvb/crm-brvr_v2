<?php

namespace App\Interfaces\Tenant\Setup\Services;

use App\Models\Tenant\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\Setup\Services\ServicesFormRequest;


interface ServicesInterface
{
    public function getAllServices($perPage): LengthAwarePaginator;

    public function getSearchedService($searchString,$perPage): LengthAwarePaginator;

    public function getServicesAnalysis(): Collection;

    public function getServicesTypes(): Collection;
    
    public function getServicesPayments(): Collection;

    public function add(ServicesFormRequest $serviceRequest): Services;

    public function update(Services $service,ServicesFormRequest $serviceRequest): int;

    public function destroy(Services $service): Services;

}
