<?php

namespace App\Http\Livewire\Tenant\Setup\Zones;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\View\View;
use App\Models\Tenant\Zones;
use Illuminate\Validation\Validator as ValidationValidator;

class EditZones extends Component
{
    public object $zones;
    // public object $customerLocation;
    public int $selectedCustomer;

    public string $name = '';
    public string $locals = '';
    public string $comercial = '';

    // public object $districts;
    // public object $counties;

    // public string $description = '';
    // public string $contact = '';
    // public string $manager_name = '';
    // public string $manager_contact = '';
    // public string $address = '';
    // public string $zipcode = '';
    // public string $main = '';
    public bool $changed;
    private bool $doRender = true;
    private string $function = '';

    protected $listeners = ['resetChanges' => 'resetChanges'];

    public function mount($zonesList): void
    {
        $this->changed = false;
        $this->initProperties($zonesList);

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

    public function save(Zones $zones)
    {
        $errorMessage = $this->validateInput();

        if ($errorMessage !== False) {

                    
        $this->dispatchBrowserEvent('swal', ['title' => __('Zones'), 'message' => $errorMessage, 'status'=>'error']);
          
        } else {
            $zones->where('id', $this->zones->id)
                ->update([
                    'name' => $this->name,
                    'locals' => $this->locals,
                    'comercial' => $this->comercial,
                ]);
    
            $this->dispatchBrowserEvent('swal', ['title' => __('Zones'), 'message' => __('Zone updated with success!'), 'status'=>'info']);
            // return redirect()->route('tenant.setup.zones.index')
            // ->with('message', __('Zone updated with success!'))
            // ->with('status', 'sucess');
        }

    }

    public function cancel()
    {
        $this->skipRender();

        if($this->changed === true) {
            $this->dispatchBrowserEvent('swal', [
                'title' => __('Zones'),
                'message' => __('You will loose all of the changes:'),
                'status' => 'warning',
                'confirm' => 'true',
                'confirmButtonText' => __('Yes, loose changes!'),
                'cancellButtonText' => __('No, keep changes!'),
            ]);
        }
    }

    public function resetChanges()
    {
        $this->initProperties($this->customerList, $this->customerLocation);
        $this->changed = false;
    }

    public function like()
    {
        dd($this);
    }

    public function saveas()
    {
        if($this->changed === true) {
            $this->initProperties($this->customerList, $this->customerLocation);
        }
    }

    public function updatedDescription()
    {
        $this->changed = true;
    }

    public function render(): View
    {
        if (old('name')) {
            $this->name = old('name');
        }
        if (old('locals')) {
            $this->locals = old('locals');
        }
        if (old('comercial')) {
            $this->comercial = old('comercial');
        }
        return view('tenant.livewire.setup.zones.edit-zones');
    }

    private function initProperties($zonesList): void
    {
        $this->zones = $zonesList;
       
        $this->name = $zonesList->name;
        $this->locals = $zonesList->locals;
        $this->comercial = $zonesList->comercial;
       

        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }
    }

    private function validateInput(): Bool | String
    {
        $validator = Validator::make(
            [
                'name'  => $this->name,
                'locals' => $this->locals,
                'comercial' => $this->comercial,
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

        $errorMessage = False;
        if ($validator->fails()) {
            $errorMessage = '<p>';
            foreach($validator->errors()->all() as $message) {
                $errorMessage .= $message . '</br>';
            }
            $errorMessage .= '</p>';
        }
        return $errorMessage;

    }

}
