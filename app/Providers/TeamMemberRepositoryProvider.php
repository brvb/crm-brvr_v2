<?php

namespace App\Providers;

use App\Interfaces\Tenant\TeamMember\TeamMemberInterface;
use App\Repositories\Tenant\TeamMember\TeamMemberRepository;
use Illuminate\Support\ServiceProvider;



class TeamMemberRepositoryProvider extends ServiceProvider
{
    public array $bindings = [
        TeamMemberInterface::class => TeamMemberRepository::class
    ];
}
