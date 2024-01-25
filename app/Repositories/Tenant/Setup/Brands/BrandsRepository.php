<?php

namespace App\Repositories\Tenant\Setup\Brands;

use App\Models\Tenant\Brands;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Setup\Brands\BrandsInterface;
use App\Http\Requests\Tenant\Setup\Brands\BrandsFormRequest;

class BrandsRepository implements BrandsInterface
{

    public function getAllBrands($perPage): LengthAwarePaginator
    {
        $brands = Brands::paginate($perPage); 

        return $brands;
    }

    public function getSearchedBrand($searchString,$perPage): LengthAwarePaginator
    {
        $brand = Brands::where('name', 'like', '%' . $searchString . '%')->paginate($perPage);

        return $brand;
    }

    public function add(BrandsFormRequest $request): Brands
    {
        return DB::transaction(function () use ($request) {

            $brand = Brands::create([
                'name' => $request->name,
                'image' => $request->patch,
            ]);

            return $brand;
        });
    }

    public function update(Brands $brand,BrandsFormRequest $request): Brands
    {     
        return DB::transaction(function () use ($brand,$request) {
            $brand->fill($request->all());
            $brand->save();
            return $brand;
        });
    }

    public function destroy(Brands $brand): Brands
    {
      
        return DB::transaction(function () use ($brand) {
            
            $brand->delete();

            return $brand;
        });
    }

}
