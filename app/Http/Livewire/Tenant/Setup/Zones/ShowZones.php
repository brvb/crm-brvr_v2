<?php

namespace App\Http\Livewire\Tenant\Setup\Zones;

use Livewire\Component;
use App\Models\Tenant\Zones;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use App\Interfaces\Tenant\Setup\Zones\ZonesInterface;

class ShowZones extends Component
{
    use WithPagination;

    private object $zones;
    public int $perPage;
    public string $searchString = '';

    protected object $zonesRepository;

    public function boot(ZonesInterface $zone)
    {
        $this->zonesRepository = $zone;
    }

    public function mount(): void
    {
        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
        session()->put('perPage', $this->perPage);
    }

    public function updatedSearchString(): void
    {
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'tenant.livewire.setup.pagination';
    }

    public function render(): View
    {
        if(isset($this->searchString) && $this->searchString) {

            $this->zones = $this->zonesRepository->getSearchedZone($this->searchString,$this->perPage);
        } else {
            
            $this->zones = $this->zonesRepository->getAllZones($this->perPage);
        }

        return view('tenant.livewire.setup.zones.show-zones', [
            'zones' => $this->zones,
        ]);
    }
}
