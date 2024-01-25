<?php

namespace App\Http\Livewire\Tenant\Analysis;

use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\ExportTasksExcel;
use App\Models\Tenant\TeamMember;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\Tenant\Analysis\AnalysisInterface;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Interfaces\Tenant\Setup\Services\ServicesInterface;
use App\Interfaces\Tenant\CustomerLocation\CustomerLocationsInterface;

class ShowTasksReports extends Component
{
    use WithPagination;

    public int $perPage;
    public string $searchString = '';

    protected object $analysisRepository;
    protected object $teamMembersRepository;
    protected object $customersRepository;
    protected object $serviceRepository;

    private ?object $analysis =  NULL;
    private ?object $members = NULL;
    private ?object $customers = NULL;
    private ?object $service = NULL;
    /** Filtro */
    private ?object $analysisToExcel = NULL;
    /***Fim Filtro */

    public int $technical = 0;
    public int $client = 0;
    public int $work = 0;

    //Filtro
    public int $typeTask = 4;
    //Fim do Filtro

    public string $dateBegin = '';
    public string $dateEnd = '';

    //Filtro
    public int $flagRender = 0;


    public function boot(AnalysisInterface $interfaceAnalysis, TeamMemberInterface $interfaceTeamMember, CustomersInterface $interfaceCustomers, ServicesInterface $interfaceService)
    {
        $this->analysisRepository = $interfaceAnalysis;
        $this->teamMembersRepository = $interfaceTeamMember;
        $this->customersRepository = $interfaceCustomers;
        $this->serviceRepository = $interfaceService;
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

        $this->members = $this->teamMembersRepository->getTeamMembersAnalysis();
        $this->customers = $this->customersRepository->getCustomersAnalysis();
        $this->service = $this->serviceRepository->getServicesAnalysis();
        $this->analysis = $this->analysisRepository->getAllAnalysis($this->perPage);
        $this->analysisToExcel = $this->analysisRepository->getAllAnalysisToExcel();

        $this->flagRender = 0;


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
        $this->members = $this->teamMembersRepository->getTeamMembersAnalysis();
        $this->customers = $this->customersRepository->getCustomersAnalysis();
        $this->service = $this->serviceRepository->getServicesAnalysis();
        $this->analysis = $this->analysisRepository->getAllAnalysis($this->perPage);
        //Filtro
        $this->analysisToExcel = $this->analysisRepository->getAllAnalysisToExcel();
        //End filtro

        $this->technical = 0;
        $this->client = 0;
        $this->work = 0;
        /***Filtro */
        $this->typeTask = 4;
        /***End Filtro */
        $this->dateBegin = '';
        $this->dateEnd = '';

        $this->flagRender = 0;

    }

    public function updatedTechnical(): void
    {
        //$this->analysis = $this->analysisRepository->getAnalysisFilter($this->technical,$this->client,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
        
        //Filtro
        $this->flagRender = 1;
    }

    public function updatedClient(): void
    {
        //$this->analysis = $this->analysisRepository->getAnalysisFilter($this->technical,$this->client,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);

        //Filtro
        $this->flagRender = 1;
    }

    public function updatedTypeTask(): void
    {
        $this->flagRender = 1;
    }

    public function updatedWork(): void
    {
        //$this->analysis = $this->analysisRepository->getAnalysisFilter($this->technical,$this->client,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);

        //Filtro
        $this->flagRender = 1;
    }

    public function updatedDateBegin(): void
    {
        //$this->analysis = $this->analysisRepository->getAnalysisFilter($this->technical,$this->client,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
        $this->dispatchBrowserEvent("contentChanged");

        //Filtro
        $this->flagRender = 1;

    }

    public function updatedDateEnd(): void
    {
        //$this->analysis = $this->analysisRepository->getAnalysisFilter($this->technical,$this->client,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
        $this->dispatchBrowserEvent("contentChanged");

        $this->flagRender = 1;
        $this->resetPage();
    }


    public function paginationView()
    {
        return 'tenant.livewire.setup.pagination';
    }

    /**Export to Excel function */
    public function exportExcel($analysis)
    {
        $this->analysis = $this->analysisRepository->getAllAnalysis($this->perPage);
        return Excel::download(new ExportTasksExcel($analysis), 'export-'.date('Y-m-d').'.xlsx');
    }


    /****** */

    /**
     * List informations of customer location
     *
     * @return View
     */
    public function render(): View
    {
        $this->members = $this->teamMembersRepository->getTeamMembersAnalysis();
        $this->customers = $this->customersRepository->getCustomersAnalysis();
        $this->service = $this->serviceRepository->getServicesAnalysis();

        if($this->flagRender == 0)
        {
            $this->analysis = $this->analysisRepository->getAllAnalysis($this->perPage);
            $this->analysisToExcel = $this->analysisRepository->getAllAnalysisToExcel();
        }
        else {
            $this->analysis = $this->analysisRepository->getAnalysisFilter($this->technical,$this->client,$this->typeTask,$this->work,$this->dateBegin,$this->dateEnd,$this->perPage);
            $this->analysisToExcel = $this->analysisRepository->getAnalysisFilterToExcel($this->technical,$this->client,$this->typeTask,$this->work,$this->dateBegin,$this->dateEnd);
        }

        return view('tenant.livewire.analysis.show-tasks-reports', [
            'analysis' => $this->analysis,
            'analysisExcel' => $this->analysisToExcel,
            'members' => $this->members,
            'customers' => $this->customers,
            'services' => $this->service
        ]);
    }
}
