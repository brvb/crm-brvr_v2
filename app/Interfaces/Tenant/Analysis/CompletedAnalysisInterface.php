<?php

namespace App\Interfaces\Tenant\Analysis;

use App\Models\Tenant\Customers;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompletedAnalysisInterface
{
    public function getAllAnalysis($perPage): LengthAwarePaginator;

    public function getAnalysisFromClient(Customers $customer,int $tech, int $work, string $dateBegin,string $dateEnd, $perPage): LengthAwarePaginator;

    public function getAnalysisFilter(int $tech,int $client,int $work,string $dateBegin,string $dateEnd,$perPage): LengthAwarePaginator;

    


    public function getAllAnalysisToExcel($all): Collection;

    public function getAllAnalysisToExcelSearchString($all,$searchString): Collection;

    public function getAnalysisFilterToExcel($all,string $searchString,int $tech,int $client,int $typeReport,int $work,$ordenation,string $dateBegin,string $dateEnd): Collection;


}
