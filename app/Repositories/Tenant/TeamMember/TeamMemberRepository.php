<?php

namespace App\Repositories\Tenant\TeamMember;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Tenant\TeamMember;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\TasksReports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Http\Requests\Tenant\TeamMember\TeamMemberFormRequest;

class TeamMemberRepository implements TeamMemberInterface
{
    public function getAllTeamMembers($perPage): LengthAwarePaginator
    {
        if(Auth::user()->type_user == 1)
        {
            $teamMembers = TeamMember::where('user_id',Auth::user()->id)->paginate($perPage);
        }
        else {
            $teamMembers = TeamMember::paginate($perPage);
        }
        return $teamMembers;
    }

    public function getTeamMembersAnalysis(): Collection
    {
        $teamMember = TeamMember::all();
        return $teamMember;
    }

    public function getSearchedTeamMembers($searchString, $perPage): LengthAwarePaginator
    {
        $teamMembers = TeamMember::where('name', 'like', '%' . $searchString . '%')->paginate($perPage);
        return $teamMembers;
    }

    public function add(TeamMemberFormRequest $request): TeamMember
    {
        return DB::transaction(function () use ($request) {

            if(!isset($request->checkstatus) || $request->checkstatus == "off")
            {
                $checkstatus = 0;
            }
            else
            {
                $checkstatus = 1;
            }


            $teamMember = TeamMember::create([
                'checkstatus' => $checkstatus,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'mobile_phone' => $request->mobile_phone,
                'job' => $request->job,
                'additional_information' => $request->additional_information,
                'color' => $request->color,
                'account_active' => '0',
                'id_hierarquia' => $request->id_hierarquia,
                'id_departamento' => $request->id_departamento
            ]);
            return $teamMember;
        });
    }

    public function update(TeamMember $teamMember, TeamMemberFormRequest $request) : TeamMember
    {

        return DB::transaction(function () use ($teamMember,$request) {
           
    
            if(!isset($request->checkstatus) || $request->checkstatus == "off")
            {
                $request["checkstatus"] = 0;
            }
            elseif($request->checkstatus == "on")
            {
                $request["checkstatus"] = 1;
            }

            

            $teamMember->fill($request->all());
            $teamMember->save();

           

           

           User::where('id',$teamMember->user_id)->update([
                "username" => $request->username,
                "email" => $request->email
            ]);
            

            return $teamMember;
        });

    }

    public function destroy(TeamMember $teamMember) : TeamMember
    {
        return DB::transaction(function () use($teamMember) {

            if($teamMember->user_id != null)
            {
                User::where('id', $teamMember->user_id)->delete();
            }
            
            $teamMember->delete();

            return $teamMember;
        });
    }

    public function createLogin($teamMember): User
    {
        return DB::transaction(function () use ($teamMember){
           $password = Str::random(8);
           $hashed_password = Hash::make($password);

           $teamMember = TeamMember::where('id',$teamMember)->first();
           
        //$checkIfUserExist = User::where('email',$teamMember->email)->first();

        //    if(empty($checkIfUserExist))
        //    {
        //         $userCreate = User::create([
        //             'name' => $teamMember->name,
        //             'username' => $teamMember->username,
        //             'email' => $teamMember->email,
        //             'type_user' => '1',
        //             'password' => $hashed_password,
        //         ]);

        //         $updateTeamMember = TeamMember::where('id',$teamMember->id)->update([
        //             'user_id' => $userCreate->id,
        //             'account_active' => '1'
        //         ]);

        //         $userCreate["user"] = ['password_without_hashed' => $password];

        //    }
        //    else 
        //    {
        //         $userCreate = User::create([
        //             'name' => $teamMember->name,
        //             'username' => $teamMember->username,
        //             'email' => $teamMember->email,
        //             'type_user' => '1',
        //             'password' => $hashed_password,
        //         ]);
                
        //    }


           $userCreate = User::create([
                'name' => $teamMember->name,
                'username' => $teamMember->username,
                'email' => $teamMember->email,
                'type_user' => '1',
                'password' => $hashed_password,
           ]);

           $updateTeamMember = TeamMember::where('id',$teamMember->id)->update([
              'user_id' => $userCreate->id,
              'account_active' => '1'
           ]);

           $userCreate["user"] = ['password_without_hashed' => $password];

           return $userCreate;
        });
    }

}
