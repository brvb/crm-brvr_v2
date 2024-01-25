<?php

namespace App\Http\Controllers\Tenant\Setup;

use Illuminate\View\View;
use App\Models\Tenant\Zones;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Interfaces\Tenant\Setup\Zones\ZonesInterface;
use App\Repositories\Tenant\Setup\Zones\ZonesRepository;
use App\Http\Requests\Tenant\Setup\Zones\ZonesFormRequest;

class ZonesController extends Controller
{
    
    private ZonesInterface $zonesRepository;

    public function __construct(ZonesInterface $zoneRepository){
        $this->ZonesRepository = $zoneRepository;
    }

    /**
     * Return list of zones
     *
     * @return View
     */
    public function index(): View
    {
        return view('tenant.setup.zones.index', [
            'themeAction' => 'table_datatable_basic',
            'status' => session('status'),
            'message' => session('message'),
        ]);
    }

    /**
     * Create new zones form
     *
     * @return View
     */
    public function create(): View
    {
        return view('tenant.setup.zones.create', [
            'themeAction' => 'form_element',
            'zonesList' => Zones::all(),
        ]);
    }

    /**
     * Edit zones form
     *
     * @param Zones $zone
     * @return View
     */
    public function edit(Zones $zone): View
    {
        return view('tenant.setup.zones.edit', [
            'themeAction' => 'form_element',
            'zonesList' => $zone,
        ]);
    }

    /**
     * Insert new zone
     *
     * @param ZonesFormRequest $request
     * @return RedirectResponse
     */
    public function store(ZonesFormRequest $request): RedirectResponse
    {
        //aqui está a dar mal
        $this->ZonesRepository->add($request);

        return to_route('tenant.setup.zones.index')
            ->with('message', __('Zone created with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Update zone
     *
     * @param Zones $zone
     * @param ZonesFormRequest $request
     * @return RedirectResponse
     */
    public function update(Zones $zone, ZonesFormRequest $request): RedirectResponse
    {
        $this->zonesRepository->update($zone,$request);

        return to_route('tenant.setup.zones.index')
            ->with('message', __('Zone updated with success!'))
            ->with('status', 'sucess');
    }

    /**
     * Delete customer location
     *
     * @param Zones $zone
     * @return RedirectResponse
     */
    public function destroy(Zones $zone): RedirectResponse
    {
        
        $this->ZonesRepository->destroy($zone);
        return to_route('tenant.setup.zones.index')
            ->with('message', __('Zone deleted with success!'))
            ->with('status', 'sucess');
    }

}
