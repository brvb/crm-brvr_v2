<?php

namespace App\Interfaces\Tenant\Analysis;

use App\Models\Tenant\Customers;
use Illuminate\Support\Collection;
use App\Models\Tenant\CustomerContacts;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Tenant\CustomerContacts\CustomerContactsFormRequest;

interface AnalysisInterface
{
    public function getAllAnalysis($perPage): LengthAwarePaginator;

    public function getAnalysisFromClient(Customers $customer,int $tech, int $work, string $dateBegin,string $dateEnd, $perPage): LengthAwarePaginator;

    public function getAnalysisFilter(int $tech,int $client,int $typeReport,int $work,string $dateBegin,string $dateEnd,$perPage): LengthAwarePaginator;

    // public function getAnalysisByTechnical(int $tech,$perPage): LengthAwarePaginator;

    // public function getAnalysisByCustomer(int $client,$perPage): LengthAwarePaginator;

    // public function getAnalysisByService(int $service,$perPage): LengthAwarePaginator;

    // public function getAnalysisByDates(string $dateInitial,string $dateFinal,$perPage): LengthAwarePaginator;

    // public function getAnalysisByWorkTime(int $number_of_hours,$perPage): LengthAwarePaginator;

    //public function getAnalysisFromClientSearch(Customers $customer,$searchString,$perPage): LengthAwarePaginator;

    public function getAllAnalysisToExcel(): Collection;

    public function getAnalysisFilterToExcel(int $tech,int $client,int $typeReport,int $work,string $dateBegin,string $dateEnd): Collection;


}
