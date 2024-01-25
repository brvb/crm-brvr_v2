<?php

namespace App\Interfaces\Tenant\Setup\Zones;

use App\Models\Tenant\Zones;

use App\Models\Tenant\Services;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\Setup\Zones\ZonesFormRequest;
use App\Http\Requests\Tenant\Setup\Services\ServicesFormRequest;


interface ZonesInterface
{
    public function getAllZones($perPage): LengthAwarePaginator;

    public function getSearchedZone($searchString,$perPage): LengthAwarePaginator;

    public function add(ZonesFormRequest $request): Zones;

    public function update(Zones $zone,ZonesFormRequest $zoneRequest): Zones;

    public function destroy(Zones $zone): Zones;

}
