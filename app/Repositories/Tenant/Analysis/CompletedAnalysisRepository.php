<?php

namespace App\Repositories\Tenant\Analysis;

use App\Models\Tenant\Pedidos;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TasksTimes;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
                    if($client != "")
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
                    if($client != "")
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

   
    public function getAllAnalysisToExcel($all): Collection {

        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();

        if(Auth::user()->type_user == 2)
        {
            if($all == "")
            {
                $reportsFinished = Pedidos::where('estado',2)->where('tech_id',$teammember->id)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
            else 
            {
                $reportsFinished = Pedidos::where('tech_id',$teammember->id)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
           
        }
        else {
            if($all == "")
            {
                $reportsFinished = Pedidos::where('estado',2)
                ->with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
            else
            {
                $reportsFinished = Pedidos::
                with('customer')
                ->with('tipoEstado')
                ->with('tech')
                ->with('servicesToDo')
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
        }
        
       

        return $reportsFinished;
    }

    public function getAllAnalysisToExcelSearchString($all,$searchString): Collection
    {
        $teammember = TeamMember::where('user_id',Auth::user()->id)->first();

        if(Auth::user()->type_user == 2)
        {
            if($all == "")
            {
                $reportsFinished = Pedidos::where('estado',2)->where('tech_id',$teammember->id)
                ->with('tipoEstado')
                ->with('tech')
                ->whereHas('servicesToDo', function ($query) use ($searchString)
                {
                
                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }
    
                })
                // ->whereHas('customer', function ($query) use ($searchString)
                // {
                //     if($searchString != "")
                //     {
                //         $query->where('short_name', 'like', '%' . $searchString . '%');
                //     }
                // })
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
            else
            {
                $reportsFinished = Pedidos::where('tech_id',$teammember->id)
                ->with('tipoEstado')
                ->with('tech')
                ->whereHas('servicesToDo', function ($query) use ($searchString)
                {
                
                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }
    
                })
                // ->whereHas('customer', function ($query) use ($searchString)
                // {
                //     if($searchString != "")
                //     {
                //         $query->where('short_name', 'like', '%' . $searchString . '%');
                //     }
                // })
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
          
        }
        else if(Auth::user()->type_user == 1)
        {
            if($all == "")
            {
                $reportsFinished = Pedidos::where('tech_id',$teammember->id)
                ->with('tipoEstado')
                ->with('tech')
                ->whereHas('servicesToDo', function ($query) use ($searchString)
                {
                
                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }
    
                })
                ->whereHas('customer', function ($query) use ($searchString)
                {
                    if($searchString != "")
                    {
                        $query->where('short_name', 'like', '%' . $searchString . '%');
                    }
                })
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
            else
            {
                $reportsFinished = Pedidos::where('estado',2)->where('tech_id',$teammember->id)
                ->with('tipoEstado')
                ->with('tech')
                ->whereHas('servicesToDo', function ($query) use ($searchString)
                {
                
                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }
    
                })
                ->whereHas('customer', function ($query) use ($searchString)
                {
                    if($searchString != "")
                    {
                        $query->where('short_name', 'like', '%' . $searchString . '%');
                    }
                })
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
           
        }
        else 
        {
            if($all == "")
            {
                $reportsFinished = Pedidos::
                with('tipoEstado')
                ->with('tech')
                ->whereHas('servicesToDo', function ($query) use ($searchString)
                {
                
                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }
    
                })
                ->whereHas('customer', function ($query) use ($searchString)
                {
                    if($searchString != "")
                    {
                        $query->where('short_name', 'like', '%' . $searchString . '%');
                    }
                })
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
            else
            {
                $reportsFinished = Pedidos::where('estado',2)
                ->with('tipoEstado')
                ->with('tech')
                ->whereHas('servicesToDo', function ($query) use ($searchString)
                {
                
                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }
    
                })
                ->whereHas('customer', function ($query) use ($searchString)
                {
                    if($searchString != "")
                    {
                        $query->where('short_name', 'like', '%' . $searchString . '%');
                    }
                })
                ->with('location')
                ->orderBy('created_at','desc')
                ->get();
            }
           
        }

        
       

        return $reportsFinished;
    }

    public function getAnalysisFilterToExcel($all,$searchString,$tech,$client,$typeReport,$work,$ordenation,$dateBegin,$dateEnd): Collection
    {

        if($client != "")
        {
            $tasks = Pedidos::whereHas('tech', function ($query) use ($tech)
            {
               if($tech != 0)
               {
                   
                    $query->where('id',$tech);
                    
               }
            })
            ->whereHas('servicesToDo', function ($query) use ($work, $searchString)
            {
               
                    if($work != 0)
                    {
                         $query->where('id',$work);
                    }

                    if($searchString != "")
                    {
                        $query->where('name', 'like', '%' . $searchString . '%');
                    }

              
              
            });
            // ->whereHas('customer', function ($query) use ($searchString)
            // {
            //     if($searchString != "")
            //     {
            //         $query->where('short_name', 'like', '%' . $searchString . '%');
            //     }
            // });
            
         
                $tasks = $tasks
                ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                    $query->where('created_at','>=',$dateBegin)->where('created_at','<=',$dateEnd);
                })
                ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                    $query->where('created_at','>=',$dateBegin);
                })
                ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                    $query->where('created_at','<=',$dateEnd);
                });
    
                if(Auth::user()->type_user == 2)
                {
                   $customer = Customers::where('user_id',Auth::user()->id)->first();
                   $tasks = $tasks->where('customer_id',$customer->id);
                }


                $tasks = $tasks->where('customer_id',$client);

                $tasks = $tasks->whereHas('tipoEstado', function ($query) use ($typeReport)
                {
                
                        if($typeReport != 0)
                        {
                            $query->where('id',$typeReport);
                        }
                
                
                });

                if($all == "")
                {
                    $tasks = $tasks->where('estado',2);
                }
                else
                {
                    $tasks = $tasks;
                }
    
                if($ordenation == "asc"){
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'asc')->get();
                 }
                 else {
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'desc')->get();
                 }

        
                     
        }
        else 
        {
            $tasks = Pedidos::whereHas('tech', function ($query) use ($tech)
            {
               if($tech != 0)
               {
                   $query->where('id',$tech);
               }
            })
            // ->whereHas('customer', function ($query) use ($searchString)
            // {
            //     if($searchString != "")
            //     {
            //         $query->where('short_name', 'like', '%' . $searchString . '%');
            //     }
            // })
            ->whereHas('servicesToDo', function ($query) use ($work, $searchString)
            {
               
                    if($work != 0)
                    {
                         $query->where('id',$work);
                    }
                    if($searchString != "")
                    {
                        $query->orwhere('name', 'like', '%' . $searchString . '%');
                    }

              
            });

          
                $tasks = $tasks
                ->when($dateBegin != "" && $dateEnd != "", function($query) use($dateBegin,$dateEnd) {
                    $query->where('created_at','>=',$dateBegin)->where('created_at','<=',$dateEnd);
                })
                ->when($dateBegin != "" && $dateEnd == "", function($query) use($dateBegin) {
                    $query->where('created_at','>=',$dateBegin);
                })
                ->when($dateBegin == "" && $dateEnd != "", function($query) use ($dateEnd) {
                    $query->where('created_at','<=',$dateEnd);
                });
    
                if(Auth::user()->type_user == 2)
                {
                   $customer = Customers::where('user_id',Auth::user()->id)->first();
                   $tasks = $tasks->where('customer_id',$customer->id);
                }

                $tasks = $tasks->whereHas('tipoEstado', function ($query) use ($typeReport)
                {
                
                        if($typeReport != 0)
                        {
                            $query->where('id',$typeReport);
                        }
                
                
                });

                if($all == "")
                {
                    $tasks = $tasks->where('estado',2);
                }
                else
                {
                    $tasks = $tasks;
                }
    
                if($ordenation == "asc"){
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'asc')->get();
                 }
                 else {
                    $tasks = $tasks->with('tech')->with('servicesToDo')->with('tipoEstado')->with('customer')->with('location')->orderBy('created_at', 'desc')->get();
                 }

         
        
        }
        

        return $tasks;
    }
    

}
