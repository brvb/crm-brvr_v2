<?php

namespace App\Repositories\Tenant\Files;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tenant\Files;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant\CustomerServices;
use App\Models\Tenant\CustomerLocations;
use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Tenant\Files\FilesInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\Customers\CustomersInterface;
use App\Http\Requests\Tenant\Customers\CustomersFormRequest;


class FilesRepository implements FilesInterface
{
    public function addToDatabase($files,$customer_id,$anwser): Collection
    {

        return DB::transaction(function () use ($files,$customer_id,$anwser) {

             $teste = Files::where('customer_id',$customer_id)->get();

            
             //verifica se já existe este ficheiro ou não
             foreach($teste as $t)
             {
                foreach(json_decode($t->file) as $file)
                {
                    if($file->ficheiro == $files[0]["ficheiro"] && Auth::user()->type_user != 2)
                    {
                        Files::where('id',$t->id)->delete();
                    }
                }
             }

             if($anwser == "ok")
             {
                $permission = 1;
             }
             else if($anwser == "cancel")
             {
                $permission = 0;
             }
             //no caso de ser um insert de um cliente
             else {
                $permission = 1;
             }
             
                Files::create([
                    'file' => json_encode($files),
                    'user_id' => Auth::user()->id,
                    'permission' => $permission,
                    'customer_id' => $customer_id,
                ]);
            

            return $teste;
        });
    }

    public function removeFileFromCustomer($id): int
    {
        return DB::transaction(function () use ($id) {
            $file = Files::where('id',$id)->delete();

            return $file;
        });
    }

   

}

