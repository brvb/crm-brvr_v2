<?php

namespace App\Interfaces\Tenant\Setup\Brands;

use App\Http\Requests\Tenant\Setup\Brands\BrandsFormRequest;
use App\Models\Tenant\Brands;
use Illuminate\Pagination\LengthAwarePaginator;

interface BrandsInterface
{
    public function getAllBrands($perPage): LengthAwarePaginator;

    public function getSearchedBrand($searchString, $perPage): LengthAwarePaginator;

    public function add(BrandsFormRequest $request): Brands;

    public function update(Brands $brand, BrandsFormRequest $brandRequest): Brands;

    public function destroy(Brands $brand): Brands;

}
