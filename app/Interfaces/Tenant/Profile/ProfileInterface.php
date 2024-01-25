<?php

namespace App\Interfaces\Tenant\Profile;

use App\Models\User;
use App\Models\Tenant\TeamMember;
use App\Http\Requests\Tenant\User\UserFormRequest;
use App\Http\Requests\Tenant\TeamMember\TeamMemberFormRequest;


interface ProfileInterface
{
    public function getMemberInformation(User $user): TeamMember;

    public function updateMemberInformation(TeamMemberFormRequest $teamMember): int;

    public function updateUserInformation(UserFormRequest $userForm): User;

    public function getUser(int $id): User;

    public function insertUser(int $id, string $storedFile): int;
}
