<div class="deznav">
    <div class="deznav-scroll">
        @if (Auth::user()->type_user != '2')
            <a href="{{ route('tenant.tasks.create') }}" class="add-menu-sidebar">{{ __('New Task') }}</a>
        @endif
        <ul class="metismenu" id="menu">
            <li><a class="ai-icon" href="{{ route('tenant.dashboard') }}">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">{{ __('Dashboard') }}</span>
                </a>
            </li>
            @if (Auth::user()->type_user == '2')
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-notepad"></i>
                    <span class="nav-text">{{ __('My Information') }}</span>
                </a>
                <ul aria-expanded="false">
                    @php
                        $idCustomer = \App\Models\Tenant\Customers::where('user_id',Auth::user()->id)->first();
                    @endphp
                    <li><a href="{{ route('tenant.customers.edit',$idCustomer->slug) }}">{{ __('Edit Information') }}</a></li>
                </ul>
                </li>
            @endif
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-windows"></i>
                    <span class="nav-text">Gerir Pedidos</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('tenant.tasks.index') }}">Pedidos</a></li>
                    <li><a href="{{ route('tenant.tasks-reports.index')}}">Intervenções</a></li>
                </ul>
            </li>
            <li class="d-none"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-network"></i>
                    <span class="nav-text">{{ __('Manage Devices') }}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">{{ __('Devices') }}</a></li>
                </ul>
            </li>
            {{-- @if(Auth::user()->type_user == '0') --}}
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-notepad"></i>
                        <span class="nav-text">{{ __('Manage Services') }}</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('tenant.services.index') }}">{{ __('Services') }}</a></li>
                    </ul>
                </li>
            {{-- @endif --}}
            @if (Auth::user()->type_user != '2')
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-notepad"></i>
                    <span class="nav-text">{{ __('Analysis') }}</span>
                </a>
                <ul aria-expanded="false">
                    {{-- <li><a href="{{ route('tenant.completed.index')}}">Pedidos completos</a></li> --}}
                    <li><a href="{{ route('tenant.analysis.index') }}">Todos os pedidos</a></li>
                </ul>

            </li>
            @endif
            @if (Auth::user()->type_user != '2')
            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                   <i class="flaticon-381-notepad"></i>
                   <span class="nav-text">{{ __("Manage Files")}}</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('tenant.files.index') }}">{{ __("Files") }}</a></li>
                </ul>
            </li>
            @else
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-notepad"></i>
                    <span class="nav-text">{{ __("My Files")}}</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('tenant.files-customer.index') }}">{{ __("Files") }}</a></li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->type_user != '2')
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-id-card-1"></i>
                        <span class="nav-text">{{ __('Manage Customers') }}</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('tenant.customers.index') }}">{{ __('Customers') }}</a></li> <!-- a corrigir */ -->
                        <li><a href="{{ route('tenant.customer-locations.index') }}">{{ __('Customer Locations') }}</a></li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->type_user == '0')
                <li class="d-none"><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-networking-1"></i>
                        <span class="nav-text">{{ __('Manage Partners') }}</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="#">{{ __('Partners') }}</a></li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->type_user == '0')
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-user-9"></i>
                        <span class="nav-text">{{ __('Manage Team') }}</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('tenant.team-member.index') }}">{{ __('Team') }}</a></li>
                    </ul>
                </li>
            @endif
            @if(Auth::user()->type_user == '0')
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-settings-6"></i>
                        <span class="nav-text">{{ __('Setup') }}</span>
                    </a>
                    <ul aria-expanded="false">
                        <li class="d-none"><a href="#">{{ __('Device Models') }}</a></li>
                        <li><a href="{{ route('tenant.setup.brands.index') }}">{{ __('Device Brands') }}</a></li>
                        <li><a href="{{ route('tenant.setup.custom-types.index') }}">{{ __('Custom types') }}</a></li>
                        <li class="d-none"><a href="#">{{ __('Parts') }}</a></li>
                        <li class="d-none"><a href="#">{{ __('Attributes') }}</a></li>
                        <li class="d-none"><a href="#">{{ __('Attributes Values') }}</a></li>
                        <li><a href="{{ route('tenant.setup.services.index') }}">{{ __('Services')}}</a></li>
                        <li><a href="{{ route('tenant.setup.zones.index') }}">{{ __('Zones')}}</a></li>
                        <li><a href="{{ route('tenant.setup.app') }}">{{ __('Config')}}</a></li>
                    </ul>
                </li>
            @endif
        </ul>
        <div class="copyright">
            <small><strong>{{ session('company_name') }}</strong></small>
            <p>© {{ date('Y') }} {{ __('All Rights Reserved') }}</p>
        </div>
    </div>
</div>
