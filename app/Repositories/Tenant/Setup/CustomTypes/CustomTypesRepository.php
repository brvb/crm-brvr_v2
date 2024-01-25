<?php

namespace App\Repositories\Tenant\Setup\CustomTypes;

use App\Models\Tenant\CustomTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Setup\CustomTypes\CustomTypesInterface;
use App\Http\Requests\Tenant\Setup\CustomTypes\CustomTypesFormRequest;

class CustomTypesRepository implements CustomTypesInterface
{

    public function getAllCustomTypes($perPage): LengthAwarePaginator
    {
        $customType = customtypes::paginate($perPage);

        return $customType;
    }

    public function getSearchedCustomType($searchString,$perPage): LengthAwarePaginator
    {
        $customType = Customtypes::
        Where('description', 'like', '%' . $searchString . '%')
        ->paginate($perPage);

        return $customType;
    }


    public function add(CustomTypesFormRequest $request): CustomTypes
    {
        return DB::transaction(function () use ($request) {
            $customType = CustomTypes::create([
                'description' => $request->description,
                'controller' => $request->controller,
                'field_name' => $request->field_name,
            ]);
            return $customType;
        });
    }

    public function update(CustomTypes $customType,CustomTypesFormRequest $request): CustomTypes
    {
        return DB::transaction(function () use ($customType,$request) {
            $customType->fill($request->all());
            $customType->save();
            return $customType;
        });
    }

    public function destroy(CustomTypes $customType): CustomTypes
    {
        return DB::transaction(function () use ($customType) {
            $customType->delete();

            return $customType;
        });
    }

}
