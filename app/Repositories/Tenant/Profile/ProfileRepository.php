<?php

namespace App\Repositories\Tenant\Profile;

use App\Models\User;
use App\Models\Tenant\Customers;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\Tenant\User\UserFormRequest;
use App\Interfaces\Tenant\Profile\ProfileInterface;
use App\Interfaces\Tenant\Analysis\AnalysisInterface;

class ProfileRepository implements ProfileInterface
{
    public function getMemberInformation($user): TeamMember {
        
        return DB::transaction(function () use ($user) {

            $memberInformation = TeamMember::where('user_id',$user)->first();

            if($memberInformation == null)
            {
                $user_login = User::where('id',$user)->first();

                
                if($user_login != null)
                {
                    $memberInformation = TeamMember::create([
                            "name" => $user_login->name,
                            "username" => $user_login->username,
                            "email" => $user_login->email,
                            "mobile_phone" => '',
                            "job" => '',
                            "user_id" => $user,
                            "color" => '',
                            "account_active" => "1"
                        ]);
                  
                }
                
            }
           
            return $memberInformation;
        });
    }

    public function updateMemberInformation($teamMember): int {


        return DB::transaction(function () use ($teamMember) {

        $updateTeamMember = TeamMember::where('id',$teamMember->idTeamMember)->update([
            'name' => $teamMember->name,
            'email' => $teamMember->email,
            'mobile_phone' => $teamMember->mobile_phone,
            'job' => $teamMember->job,
            'additional_information' => $teamMember->additional_information,
            'color' => $teamMember->color
        ]);

        if(isset($teamMember->photo))
        {
            User::where('id', $teamMember->user_id)->update([
                'name' => $teamMember->name,
                'email' => $teamMember->email,
                'photo' => $teamMember->photo
            ]);
        }
        else
        {
            User::where('id', $teamMember->user_id)->update([
                'name' => $teamMember->name,
                'email' => $teamMember->email
            ]);
        }

        return $updateTeamMember;
    });

    }

    public function updateUserInformation($userForm): User {
        
        $hashedPassword = Hash::make($userForm->password);
        
        return DB::transaction(function () use ($userForm, $hashedPassword) {
            $updateUser = User::where('id', $userForm->user_id)->first();



            if($updateUser->username == $userForm->username)
            {
               
                if($userForm->photo != null)
                {
                    $arrayToUpdate = ["photo" => $userForm->photo,"password" => $hashedPassword]; 
                }
                else 
                {
                    $arrayToUpdate = ["password" => $hashedPassword];
                    
                }
            }
            else
            {
                             
                $arrayToUpdate = ["username" => $userForm->username, "password" => $hashedPassword]; 
                              
                
                 if(Auth::user()->type_user == 2)
                 {
                    Customers::where('user_id',Auth::user()->id)->update([
                        "username" => $userForm->username
                     ]);
                 }
                 else {
                    $updateTeamMember = TeamMember::where('user_id',$userForm->user_id)->update([
                        "username" => $userForm->username
                     ]);
                 }
            }


             $updateUserInfo = User::where('id',$userForm->user_id)->update(
                $arrayToUpdate
             );

             return $updateUser;
        });
    }

    public function getUser(int $id): User 
    {
        return DB::transaction(function () use ($id) {

            $user = User::where('id',$id)->first();
            return $user;
        });

    }

    public function insertUser(int $id, string $storedFile): int
    {
        return DB::transaction(function () use ($id,$storedFile){

           $user = User::where('id',$id)->update([
                'photo' => $storedFile
            ]);

            return $user;
        });
    }

      
}
