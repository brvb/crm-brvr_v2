<?php

namespace App\Http\Livewire\Tenant\Common;

use Livewire\Component;
use App\Models\Counties;
use App\Models\Districts;
use Illuminate\Contracts\View\View;

class Location extends Component
{
    private object $districts;
    private $counties;

    public $district = '';
    public $county = '';

    protected $listeners = [
        'updatedDistrict'
     ];

    public function mount($districts, $counties, $district, $county): void
    {
        $this->districts = $districts;
        $this->counties = $counties;
        $this->district = $district;
        $this->county = $county;

        if($this->county != "")
        {
            $this->counties = Counties::where('district_id', $this->district)->get();
        }

        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }
    }

    public function updatedDistrict($districtId)
    {
        // $this->districts  = tenancy()->central(function () {
        //     return Districts::all();
        // });
        $this->districts = Districts::all();
        // $this->counties = tenancy()->central(function () use ($districtId) {
        //     return Counties::where('district_id', $districtId)->get();
        // });
        $this->counties = Counties::where('district_id', $districtId)->get();
    }

    public function render(): View
    {
        return view('tenant.livewire.common.location', [
            'districts' => $this->districts,
            'counties' => $this->counties,
            'district' => $this->district,
            'county' => $this->county
        ]);
    }
}
