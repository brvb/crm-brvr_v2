<?php

namespace App\Http\Livewire\Tenant\Setup\Zones;

use App\Models\Tenant\Zones;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;


class AddZones extends Component
{
    use WithPagination;

    private object $zones;
    public int $perPage;
    public string $searchString = '';

    // public object $zonesList;
    // public object $serviceList;
    // public string $selectedCustomer = '';
    // public string $selectedService = '';
    // public $customer = '';
    // public $service = '';
    // public string $start_date = '';
    // public string $end_date = '';
    // public string $type = '';

    public string $homePanel = 'show active';
    public string $locationPanel = '';
    public string $profile = '';

    public string $name = '';
    public string $locals = '';
    public string $comercial = '';

    public function mount($zonesList): void
    {
        $this->zonesList = $zonesList;
         
        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }

        if (old('name')) {
            $this->name = old('name');
        }
        if (old('locals')) {
            $this->locals = old('locals');
        }
        if (old('comercial')) {
            $this->comercial = old('comercial');
        }
    }

    // public function updatedPerPage(): void
    // {
    //     $this->resetPage();
    //     session()->put('perPage', $this->perPage);
    // }

    // public function updatedSearchString(): void
    // {
    //     $this->resetPage();
    // }

    // public function paginationView()
    // {
    //     return 'tenant.livewire.setup.pagination';
    // }

    // public function updatedSelectedCustomer()
    // {
    //     $this->customer = Customers::where('id', $this->selectedCustomer)->first();
    //     $this->homePanel = 'show active';
    //     $this->locationPanel = '';
    //     $this->profile = '';
    //     //$this->dispatchBrowserEvent('contentChanged');
    // }

    // public function updatedSelectedService()
    // {
    //     $this->service = Services::where('id', $this->selectedService)->first();
    //     $this->homePanel = '';
    //     $this->locationPanel = 'show active';
    //     $this->profile = '';
    //     $this->dispatchBrowserEvent('contentChanged');
    // }

    // public function updatedStartDate()
    // {
    //     //$this->dispatchBrowserEvent('contentChanged2');
    // }

    // public function updatedEndDate()
    // {
    //     //$this->dispatchBrowserEvent('contentChanged2');
    // }

    public function save(Zones $zones)
    {
        $validator = Validator::make(
            [
                'name'  => $this->name,
                'locals' => $this->locals,
                'comercial' => $this->comercial
            ],
            [
                'name'  => 'required|min:1',
                'locals' => 'required|min:1',
                'comercial' => 'required|min:1',
            ],
            [
                'name'  => __('You must select a name!'),
                'locals' => __('You must select a local!'),
                'comercial' => __('You must select a comercial!'),
            ]
        );

        if ($validator->fails()) {
            $errorMessage = '';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= '<p>' . $message . '</p>';
            }
            $this->dispatchBrowserEvent('swal', ['title' => __('Zones'), 'message' => $errorMessage, 'status'=>'error']);
        } else {
        
            $zones->fill([
                'name' => $this->name,
                'locals' => $this->locals,
                'comercial' => $this->comercial,
            ]);
            $zones->save();
            return redirect()->route('tenant.setup.zones.index')
                ->with('message', __('Zone created with success!'))
                ->with('status', 'sucess');
        }

    }

    public function render(): View
    {
        if(isset($this->searchString) && $this->searchString) {
            $this->zones = Zones::where('name', 'like', '%' . $this->searchString . '%')
                ->with('customer')
                ->with('service')
                ->paginate($this->perPage);
        } else {
            $this->zones = Zones::paginate($this->perPage);
        }

        return view('tenant.livewire.setup.zones.add-zones', [
            'name' => $this->name,
            'locals' => $this->locals,
            'comercial' => $this->comercial,
        ]);
    }

}
