<?php

namespace App\Interfaces\Tenant\TeamMember;

use App\Models\Tenant\TeamMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use App\Http\Requests\Tenant\TeamMember\TeamMemberFormRequest;
use App\Models\User;

interface TeamMemberInterface
{
    public function getAllTeamMembers($perPage): LengthAwarePaginator;

    public function getTeamMembersAnalysis(): Collection;

    public function getSearchedTeamMembers($searchString,$perPage): LengthAwarePaginator;

    public function add(TeamMemberFormRequest $request): TeamMember;

    public function update(TeamMember $teamMember,TeamMemberFormRequest $request): TeamMember;

    public function destroy(TeamMember $teammember);

    public function createLogin(string $teamMember): User;

}
