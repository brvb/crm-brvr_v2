<?php

namespace App\Repositories\Tenant\Analysis;

use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Analysis\AnalysisInterface;


class AnalysisRepository implements AnalysisInterface
{
    public function getAllAnalysis($perPage): LengthAwarePaginator {

        $reportsFinished = TasksTimes::whereHas('tasksReports', function ($query) {
            //$query->where('reportStatus',2);
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

    public function getAnalysisFilter($tech,$client,$typeReport,$work,$dateBegin,$dateEnd,$perPage): LengthAwarePaginator
    {
        // $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client) {
        //     $query->where('reportStatus',2);
        //     $query->WhereHas('tech', function ($queryy) use ($tech){
        //         if($tech != 0)
        //         {
        //             $queryy->Where('id',$tech);
        //         }
        //     });
        //     $query->whereHas('taskCustomer', function ($queryy) use ($client){
        //         if($client != 0)
        //         {
        //             $queryy->Where('id',$client);
        //         }
        //      });
        // })
        // ->whereHas('service', function ($query) use ($work){
        //     if($work != 0)
        //     {
        //         $query->where('id',$work);
        //     }
        // })
        // ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
        //     $query->where('date_begin','>=',$dateBegin)->where('date_end','<=',$dateEnd);
        // })
        // ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
        //     $query->where('date_begin','>=',$dateBegin);
        // })
        // ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
        //     $query->where('date_end','<=',$dateEnd);
        // })
        // ->with('tasksReports')
        // ->with('service')
        // ->paginate($perPage);

        if($tech != 0)
        {
            $teamMember = TeamMember::where('id',$tech)->first();


            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client,$typeReport) {
                if($typeReport != 4)
                {
                    $query->where('reportStatus',$typeReport);
                }
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
            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client,$typeReport) {
                if($typeReport != 4)
                {
                    $query->where('reportStatus',$typeReport);
                }
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
            ->paginate($perPage);
        }

        return $reportsFromClient;
    }

    // public function getAnalysisByTechnical($tech,$perPage): LengthAwarePaginator
    // {
    //     $analysisTechnical = TasksTimes::whereHas('tasksReports', function ($query) use ($tech){
    //         $query->where('reportStatus',2);
    //         $query->WhereHas('tech', function ($queryy) use ($tech){
    //             $queryy->where('id',$tech)->orderBy('name','ASC');
    //         });
    //     })
    //     ->with('tasksReports')
    //     ->with('service')
    //     ->paginate($perPage);

    //     return $analysisTechnical;
    // }

    // public function getAnalysisByCustomer($client,$perPage): LengthAwarePaginator
    // {
    //     $analysisCustomer = TasksTimes::whereHas('tasksReports', function ($query) use ($client){
    //         $query->where('reportStatus',2);
    //         $query->whereHas('taskCustomer', function ($queryy) use ($client){
    //             $queryy->where('id',$client)->orderBy('short_name','ASC');
    //         });
    //     })
    //     ->with('tasksReports')
    //     ->with('service')
    //     ->paginate($perPage);

    //     return $analysisCustomer;
    // }

    // public function getAnalysisByService($service,$perPage): LengthAwarePaginator
    // {
    //     $analysisServices = TasksTimes::whereHas('service', function ($query) use ($service){
    //         $query->where('id',$service);
    //     })
    //     ->whereHas('tasksReports', function ($queryy) {
    //         $queryy->where('reportStatus',2);
    //     })
    //     ->with('tasksReports')
    //     ->with('service')
    //     ->paginate($perPage);

    //     return $analysisServices;
    // }

    // public function getAnalysisByDates($firstDate,$secondDate,$perPage): LengthAwarePaginator
    // {
    //     $analysisServices = TasksTimes::whereHas('tasksReports',function($query) {
    //         $query->where('reportStatus',2);
    //     })
    //     ->whereBetween('date_begin',[$firstDate,$secondDate])
    //     ->with('tasksReports')
    //     ->with('service')
    //     ->paginate($perPage);

    //     return $analysisServices;
    // }

    // public function getAnalysisByWorkTime($number_of_hours,$perPage): LengthAwarePaginator
    // {
    //     dd($number_of_hours);
    //     $analysisWorkTime = TasksTimes::whereHas('tasksReports',function($query) {
    //         $query->where('reportStatus',2);
    //     })
    //     ->where('total_hours','like',$number_of_hours+'%')
    //     ->with('tasksReports')
    //     ->with('service')
    //     ->paginate($perPage);

    //     dd($analysisWorkTime);

    //     return $analysisWorkTime;
    // }

    // public function getAnalysisFromClientSearch($customer,$searchString,$perPage): LengthAwarePaginator
    // {
    //     $reportsFromClient = TasksTimes::whereHas('tasksReports', function($query) use ($customer,$searchString) {
    //         $query->where('reportStatus',2)->where('customer_id',$customer->id);
    //     })
    //     ->with('tasksReports')
    //     ->whereHas('service', function($query) use ($searchString) {
    //         $query->where('name','like','%'.$searchString.'%');
    //     })
    //     ->with('service')
    //     ->paginate($perPage);

    //     return $reportsFromClient;
    // }

    public function getAllAnalysisToExcel(): Collection {

        $reportsFinished = TasksTimes::whereHas('tasksReports', function ($query){

        })
        ->with('tasksReports')
        ->with('service')
        ->where('date_end','!=',null)
        ->get();

        return $reportsFinished;
    }

    public function getAnalysisFilterToExcel($tech,$client,$typeReport,$work,$dateBegin,$dateEnd): Collection
    {

        if($tech != 0)
        {
            $teamMember = TeamMember::where('id',$tech)->first();

            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client,$typeReport) {
                if($typeReport != 4)
                {
                    $query->where('reportStatus',$typeReport);
                }
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
            $reportsFromClient = TasksTimes::whereHas('tasksReports', function ($query) use ($tech,$client,$typeReport) {
                if($typeReport != 4)
                {
                    $query->where('reportStatus',$typeReport);
                }
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
