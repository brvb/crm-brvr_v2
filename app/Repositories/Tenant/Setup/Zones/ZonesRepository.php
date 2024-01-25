<?php

namespace App\Repositories\Tenant\Setup\Zones;

use App\Models\Tenant\Zones;
use Illuminate\Support\Facades\DB;
use App\Interfaces\Tenant\Setup\Zones\ZonesInterface;
use App\Http\Requests\Tenant\Setup\Zones\ZonesFormRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class ZonesRepository implements ZonesInterface
{
   public function getAllZones($perPage): LengthAwarePaginator
   {
      $zones = Zones::paginate($perPage);

      return $zones;
   }

   public function getSearchedZone($searchString,$perPage): LengthAwarePaginator
   {
      $zones = Zones::where('name', 'like', '%' . $searchString . '%')
      ->orWhere('locals','like','%' . $searchString . '%')
      ->orWhere('comercial', 'like', '%' . $searchString . '%')
      ->paginate($perPage);

      return $zones;
   }

    public function add(ZonesFormRequest $request): Zones
    {
        return DB::transaction(function () use ($request) {
            $zones = Zones::create([
                'name' => $request->name,
                'locals' => $request->locals,
                'comercial' => $request->comercial,
            
            ]);
            return $zones;
        });
    }

    public function update(Zones $zone, ZonesFormRequest $request): Zones
    {
        return DB::transaction(function () use ($zone,$request) {
            $zone->fill($request->all());
            $zone->save();
        
            return $zone;
        });
        
    }

    public function destroy(Zones $zone): Zones
    {
        return DB::transaction(function () use ($zone) {
        $zone->delete();
        return $zone;
        });
        
    }

  
}
