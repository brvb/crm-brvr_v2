<?php

namespace App\Interfaces\Tenant\Setup\CustomTypes;

use App\Models\Tenant\Brands;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\Setup\Brands\BrandsFormRequest;
use App\Http\Requests\Tenant\Setup\CustomTypes\CustomTypesFormRequest;
use App\Models\Tenant\CustomTypes;

interface CustomTypesInterface
{
    public function getAllCustomTypes($perPage): LengthAwarePaginator;

    public function getSearchedCustomType($searchString,$perPage): LengthAwarePaginator;

    public function add(CustomTypesFormRequest $request): CustomTypes;

    public function update(CustomTypes $customType,CustomTypesFormRequest $request): CustomTypes;

    public function destroy(CustomTypes $customType): CustomTypes;

}
