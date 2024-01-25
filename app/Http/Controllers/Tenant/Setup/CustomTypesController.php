<?php

namespace App\Http\Controllers\Tenant\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Setup\CustomTypes\CustomTypesFormRequest;
use App\Interfaces\Tenant\Setup\CustomTypes\CustomTypesInterface;
use App\Repositories\Tenant\Setup\CustomTypes\CustomTypesRepository;
use Illuminate\Http\Request;
use App\Models\Tenant\CustomTypes;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CustomTypesController extends Controller
{
    private CustomTypesInterface $customTypeRepository;

    public function __construct(CustomTypesInterface $customTypeRepository){
        $this->customTypeRepository = $customTypeRepository;
    }

      /**
     * Display the custom type list.

     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('tenant.setup.customtypes.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    /**
     * Display custom type create page
     *
     * @return View
     */
    public function create(): View
    {
        $themeAction = 'form_element';
        return view('tenant.setup.customtypes.create', compact('themeAction'));
    }

    /**
     * Display edit custom type page
     *
     * @param CustomTypes $customType
     * @return View
     */
    public function edit(CustomTypes $customType): View
    {
        $themeAction = 'form_element';
        return view('tenant.setup.customtypes.edit', compact('customType', 'themeAction'));
    }

    /**
     * Stores in database a custom type
     *
     * @param CustomTypesFormRequest $request
     * @return RedirectResponse
     */
    public function store(CustomTypesFormRequest $request): RedirectResponse
    {
        $this->customTypeRepository->add($request);

        return to_route('tenant.setup.custom-types.index')
            ->with('message', __('Custom type created with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Updates a custom type from database
     *
     * @param CustomTypes $customType
     * @param CustomTypesFormRequest $request
     * @return RedirectResponse
     */
    public function update(CustomTypes $customType, CustomTypesFormRequest $request): RedirectResponse
    {
       
        $this->customTypeRepository->update($customType,$request);

        return to_route('tenant.setup.custom-types.index')
            ->with('message', __('Custom type updated with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Delete a custom type from database
     *
     * @param CustomTypes $customType
     * @return RedirectResponse
     */
    public function destroy(CustomTypes $customType): RedirectResponse
    {
        // $customType->delete();
        $this->customTypeRepository->destroy($customType);
        return to_route('tenant.setup.custom-types.index')
            ->with('message', __('Custom type deleted with success!'))
            ->with('status', 'sucess');
    }

}
