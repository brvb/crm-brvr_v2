<?php

namespace App\Http\Livewire\Tenant\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant\Customers;
use App\Interfaces\Tenant\Analysis\AnalysisInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Interfaces\Tenant\Setup\Services\ServicesInterface;

class ShowReportsPanel extends Component
{
    use WithPagination;

    public int $perPage;

    public string $searchString = '';

    public Customers $customer;

    protected object $analysisReportsRepository;

    private ?object $reportsFromClient = NULL;

    protected object $teamMembersRepository;
    protected object $customersRepository;
    protected object $serviceRepository;

    private ?object $analysis =  NULL;
    private ?object $members = NULL;
    private ?object $customers = NULL;
    private ?object $service = NULL;

    public int $technical = 0;
    public int $client = 0;
    public int $work = 0;

    public string $dateBegin = '';
    public string $dateEnd = '';

    public function boot(AnalysisInterface $analysis,TeamMemberInterface $interfaceTeamMember, ServicesInterface $interfaceService)
    {
        $this->analysisReportsRepository = $analysis;
        $this->teamMembersRepository = $interfaceTeamMember;
        $this->serviceRepository = $interfaceService;
    }

    public function mount($customer)
    {
        $this->customer = $customer;

        if (isset($this->perPage)) {
            session()->put('perPage', $this->perPage);
        } elseif (session('perPage')) {
            $this->perPage = session('perPage');
        } else {
            $this->perPage = 10;
        }

        $this->reportsFromClient = $this->analysisReportsRepository->getAnalysisFromClient($this->customer,$this->technical,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);

        $this->members = $this->teamMembersRepository->getTeamMembersAnalysis();
        $this->service = $this->serviceRepository->getServicesAnalysis();
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

    public function clearFilter(): void
    {
        $this->technical = 0;
        $this->work = 0;
        $this->dateBegin = '';
        $this->dateEnd = '';
        
        $this->members = $this->teamMembersRepository->getTeamMembersAnalysis();
        $this->service = $this->serviceRepository->getServicesAnalysis();
        $this->reportsFromClient = $this->analysisReportsRepository->getAnalysisFromClient($this->customer,$this->technical,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);

       
    }

    public function updatedTechnical(): void
    {
        $this->reportsFromClient = $this->analysisReportsRepository->getAnalysisFromClient($this->customer,$this->technical,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
    }

    public function updatedWork(): void
    {
        $this->reportsFromClient = $this->analysisReportsRepository->getAnalysisFromClient($this->customer,$this->technical,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
    }

    public function updatedDateBegin(): void
    {
        $this->reportsFromClient = $this->analysisReportsRepository->getAnalysisFromClient($this->customer,$this->technical,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
    }

    public function updatedDateEnd(): void
    {
        $this->reportsFromClient = $this->analysisReportsRepository->getAnalysisFromClient($this->customer,$this->technical,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
    }

    public function paginationView()
    {
        return 'tenant.livewire.setup.pagination';
    }

    public function render()
    {
        $this->members = $this->teamMembersRepository->getTeamMembersAnalysis();
        $this->service = $this->serviceRepository->getServicesAnalysis();

        return view('tenant.livewire.customers.show-reports-panel',[
            "reportsFromClient" => $this->reportsFromClient,
            "members" => $this->members,
            "customers" => $this->customers,
            "services" => $this->service
        ]);
    }
}
