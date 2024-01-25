<?php

namespace App\Repositories\Tenant\Analysis;

use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Analysis\CompletedAnalysisInterface;



class CompletedAnalysisRepository implements CompletedAnalysisInterface
{
    public function getAllAnalysis($perPage): LengthAwarePaginator {

        $reportsFinished = TasksTimes::whereHas('tasksReports', function ($query) {
            $query->where('reportStatus',2);
        })
        ->with('tasksReports')
        ->with('service')
        ->where('date_end','!=',null)
        ->paginate($perPage);

        return $reportsFinished;
    }

    public function getAnalysisFromClient($customer,$tech,$work,$dateBegin,$dateEnd, $perPage): LengthAwarePaginator
    {
        $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($customer,$tech) {
            $query->where('reportStatus',2)->where('customer_id',$customer->id);
            $query->whereHas('tech', function ($queryy) use ($tech){
                if($tech != 0)
                {
                    $queryy->where('id',$tech);
                }
            });
        })
        ->whereHas('service', function ($query) use ($work){
            if($work != 0)
            {
                $query->where('id',$work);
            }
        })
        ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
            $query->where('date_begin','>=',$dateBegin)->where('date_end','<=',$dateEnd);
        })
        ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
            $query->where('date_begin','>=',$dateBegin);
        })
        ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
            $query->where('date_end','<=',$dateEnd);
        })
        ->with('tasksReports')
        ->with('service')
        ->where('date_end','!=',null)
        ->paginate($perPage);

        return $reportsFromClient;
    }

    public function getAnalysisFilter($tech,$client,$work,$dateBegin,$dateEnd,$perPage): LengthAwarePaginator
    {

        if($tech != 0)
        {
            $teamMember = TeamMember::where('id',$tech)->first();


            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client) {
               
                $query->where('reportStatus',2);
                
                $query->whereHas('taskCustomer', function ($queryy) use ($client){
                    if($client != 0)
                    {
                        $queryy->Where('id',$client);
                    }
                 });
            })
            ->whereHas('service', function ($query) use ($work){
                if($work != 0)
                {
                    $query->where('id',$work);
                }
            })
            ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                $query->where('date_begin','>=',$dateBegin)->where('date_end','<=',$dateEnd);
            })
            ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                $query->where('date_begin','>=',$dateBegin);
            })
            ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                $query->where('date_end','<=',$dateEnd);
            })
            ->with('tasksReports')
            ->with('service')
            ->where('tech_id',$teamMember->user_id)
            ->where('date_end','!=',null)
            ->paginate($perPage);    
        }
        else 
        {
            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client) {
                
                $query->where('reportStatus',2);
                
                $query->whereHas('taskCustomer', function ($queryy) use ($client){
                    if($client != 0)
                    {
                        $queryy->where('id',$client);
                    }
                 });
            })
            ->whereHas('service', function ($query) use ($work){
                if($work != 0)
                {
                    $query->where('id',$work);
                }
            })
            ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                $query->where('date_begin','>=',$dateBegin)->where('date_end','<=',$dateEnd);
            })
            ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                $query->where('date_begin','>=',$dateBegin);
            })
            ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                $query->where('date_end','<=',$dateEnd);
            })
            ->with('tasksReports')
            ->with('service')
            ->where('date_end','!=',null)
            ->paginate($perPage);
        }

        return $reportsFromClient;
    }

   
    public function getAllAnalysisToExcel(): Collection {

        $reportsFinished = TasksTimes::whereHas('tasksReports', function ($query){
        
            $query->where('reportStatus',2);

        })
        ->with('tasksReports')
        ->with('service')
        ->where('date_end','!=',null)
        ->get();

        return $reportsFinished;
    }

    public function getAnalysisFilterToExcel($tech,$client,$work,$dateBegin,$dateEnd): Collection
    {

        if($tech != 0)
        {
            $teamMember = TeamMember::where('id',$tech)->first();

            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client) {
                            
                $query->where('reportStatus',2);
               
                $query->whereHas('taskCustomer', function ($queryy) use ($client){
                    if($client != 0)
                    {
                        $queryy->Where('id',$client);
                    }
                 });
            })
            ->whereHas('service', function ($query) use ($work){
                if($work != 0)
                {
                    $query->where('id',$work);
                }
            })
            ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                $query->where('date_begin','>=',$dateBegin)->where('date_end','<=',$dateEnd);
            })
            ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                $query->where('date_begin','>=',$dateBegin);
            })
            ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                $query->where('date_end','<=',$dateEnd);
            })
            ->with('tasksReports')
            ->with('service')
            ->where('tech_id',$teamMember->user_id)
            ->where('date_end','!=',null)
            ->get();
        }
        else 
        {
            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client) {
               
                $query->where('reportStatus',2);
                
                $query->whereHas('taskCustomer', function ($queryy) use ($client){
                    if($client != 0)
                    {
                        $queryy->Where('id',$client);
                    }
                 });
            })
            ->whereHas('service', function ($query) use ($work){
                if($work != 0)
                {
                    $query->where('id',$work);
                }
            })
            ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                $query->where('date_begin','>=',$dateBegin)->where('date_end','<=',$dateEnd);
            })
            ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                $query->where('date_begin','>=',$dateBegin);
            })
            ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                $query->where('date_end','<=',$dateEnd);
            })
            ->with('tasksReports')
            ->with('service')
            ->where('date_end','!=',null)
            ->get();
        }
        

        return $reportsFromClient;
    }
    

}
