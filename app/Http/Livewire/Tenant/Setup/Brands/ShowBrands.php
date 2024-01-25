<?php

namespace App\Http\Livewire\Tenant\Setup\Brands;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant\Brands;
use Illuminate\Contracts\View\View;
use App\Interfaces\Tenant\Setup\Brands\BrandsInterface;

class ShowBrands extends Component
{
    use WithPagination;

    private object $brands;
    public int $perPage;
    public string $searchString = '';

    protected object $brandsRepository;

    public function boot(BrandsInterface $brand)
    {
        $this->brandsRepository = $brand;
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
           // $this->brands = Brands::where('name', 'like', '%' . $this->searchString . '%')->paginate($this->perPage);
           $this->brands = $this->brandsRepository->getSearchedBrand($this->searchString,$this->perPage);
        } else {
            //$this->brands = Brands::paginate($this->perPage);
            $this->brands = $this->brandsRepository->getAllBrands($this->perPage);
        }
        return view('tenant.livewire.setup.brands.show-brands', [
            'brands' => $this->brands, 'aa' => $this->page
        ]);
    }
}
