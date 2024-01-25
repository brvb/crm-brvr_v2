<?php

namespace App\Http\Controllers\Tenant\Setup;


use Illuminate\Http\Request;
use App\Models\Tenant\Brands;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Tenant\Setup\Brands\BrandsInterface;
use App\Repositories\Tenant\Setup\Brands\BrandsRepository;
use App\Http\Requests\Tenant\Setup\Brands\BrandsFormRequest;

class BrandsController extends Controller
{

    private BrandsInterface $brandsRepository;

    public function __construct(BrandsInterface $brandsRepository){
        $this->brandsRepository = $brandsRepository;
    }
    /**
     * Display the brands list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('tenant.setup.brands.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
            ]);
    }

    /**
     * Create brand.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $themeAction = 'form_element';
        return view('tenant.setup.brands.create', compact('themeAction'));
    }

    /**
     * Open edit page for brand
     *
     * @param Brands $brand
     * @return View
     */
    public function edit(Brands $brand, Request $request): View
    {
        $themeAction = 'form_element';
        return view('tenant.setup.brands.edit', compact('brand', 'themeAction'));
    }

    /**
     * Store a brand in database
     *
     * @param BrandsFormRequest $request
     * @return RedirectResponse
     */
    public function store(BrandsFormRequest $request): RedirectResponse
    {
        if(isset($request->file))
        {
            if(!Storage::exists(tenant('id') . '/app/public/brands'))
            {
                File::makeDirectory(storage_path('/app/public/brands'), 0755, true, true);
            }
            
            $patch = $request->file->storeAs(tenant('id') . '/app/public/brands/', $request->file->getClientOriginalName());          
           $img = $request->file->getClientOriginalName();
        }
        else
        {
            $patch = "noimage";
            $img = "noimage";
        }

        //$patch
        $request->merge(["patch" => $img]);

        $this->brandsRepository->add($request);

        return to_route('tenant.setup.brands.index')
            ->with('message', __('Brand created with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Update a brand from database
     *
     * @param Brands $brand
     * @param BrandsFormRequest $request
     * @return RedirectResponse
     */
    public function update(Brands $brand, BrandsFormRequest $request): RedirectResponse
    {
        if(isset($request->file))
        {
            Storage::disk('local')->delete($request->image);

            if(!Storage::exists(tenant('id') . '/app/public/brands'))
            {
                File::makeDirectory(storage_path('/app/public/brands'), 0755, true, true);
            }
            
            $patch = $request->file->storeAs(tenant('id') . '/app/public/brands/', $request->file->getClientOriginalName());   
            //$patch = $request->file->store(tenant()->id.'/images/brands');
            $img = $request->file->getClientOriginalName();
            $request->merge(["image" => $img]);
        }

        $this->brandsRepository->update($brand,$request);

        return to_route('tenant.setup.brands.index')
            ->with('message', __('Brand updated with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Delete brand from database
     *
     * @param Brands $brand
     * @return RedirectResponse
     */
    public function destroy(Brands $brand): RedirectResponse
    {
       Storage::disk('local')->delete($brand->image);
       $this->brandsRepository->destroy($brand);

        return to_route('tenant.setup.brands.index')
            ->with('message', __('Brand deleted with success!'))
            ->with('status', 'sucess');
    }

}
