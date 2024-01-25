<?php

namespace App\Interfaces\Tenant\CustomerLocation;

use App\Http\Requests\Tenant\CustomerLocations\CustomerLocationsFormRequest;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Pagination\LengthAwarePaginator;


interface CustomerLocationsInterface
{
    public function getAllCostumerLocations($perPage): LengthAwarePaginator;

    public function getSearchedCostumerLocations($searchString,$perPage): LengthAwarePaginator;

    public function add(CustomerLocationsFormRequest $request): CustomerLocations;

    public function update(CustomerLocations $customerLocation,CustomerLocationsFormRequest $request): CustomerLocations;

    public function destroy(CustomerLocations $customerLocation): CustomerLocations;

}
