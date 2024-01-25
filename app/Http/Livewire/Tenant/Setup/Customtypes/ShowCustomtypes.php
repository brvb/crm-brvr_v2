<?php

namespace App\Http\Livewire\Tenant\Setup\Customtypes;

use App\Interfaces\Tenant\Setup\CustomTypes\CustomTypesInterface;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant\Customtypes;

class ShowCustomtypes extends Component
{
    use WithPagination;

    private object $customtypes;
    public int $perPage;
    public string $searchString = '';

    protected object $customTypeRepository;

    public function boot(CustomTypesInterface $customType)
    {
        $this->customTypeRepository = $customType;
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

    public function render()
    {
        if(isset($this->searchString) && $this->searchString) {
            $this->customtypes = $this->customTypeRepository->getSearchedCustomType($this->searchString,$this->perPage);
            // $this->customtypes = Customtypes::
            //     Where('description', 'like', '%' . $this->searchString . '%')
            //     ->paginate($this->perPage);
        } else {
            $this->customtypes = $this->customTypeRepository->getAllCustomTypes($this->perPage);
           // $this->customtypes = customtypes::paginate($this->perPage);
        }
        return view('tenant.livewire.setup.customtypes.show-customtypes', [
            'customtypes' => $this->customtypes
        ]);
    }
}
