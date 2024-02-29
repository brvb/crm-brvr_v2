
<div>
<div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
<div class="sk-three-bounce" style="background:none;">
    <div class="sk-child sk-bounce1"></div>
    <div class="sk-child sk-bounce2"></div>
    <div class="sk-child sk-bounce3"></div>
</div>
</div>
<div class="row" style="display:block!important;">
    <div id="accordion-one" class="accordion accordion-primary" wire:ignore>
        <div class="accordion__item">
            <div class="accordion__header rounded-lg collapsed" data-toggle="collapse" data-target="#default_collapseOne" aria-expanded="false">
                <span class="accordion_header--text">{{ __('Filters') }}</span>
                <span class="accordion__header--indicator"></span>
            </div>
            <div id="default_collapseOne" class="accordion__body collapse" data-parent="#accordion-one">
                <div class="accordion__body--text">
                    <div class="col-12" style="margin-bottom:25px;padding-left:0px;">
                        <div class="row">
                            <div class="col-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>Pesquise pelo Nome</label>
                                    <input type="text" class="form-control" id="nomecustomer" wire:model.debounce.300ms="nomecustomer">
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>Pesquise pelo NIF</label>
                                    <input type="text" class="form-control" id="nifcustomer" wire:model.debounce.300ms="nifcustomer">
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 col-md-4">
                                <div class="form-group">
                                    <label>Pesquise pelo Contacto</label>
                                    <input type="text" class="form-control" id="contactocustomer" wire:model.debounce.300ms="contactocustomer">
                                </div>
                            </div>
                        </div>
                        
            
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" id="clearFilter" wire:click="clearFilter" class="btn-sm btn btn-primary">{{__("Clear Filter")}}</button>
                                {{-- <button type="button" id="searchFilter" wire:click="searchFilter" class="btn-sm btn btn-primary">Pesquisar Filtro</button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="table-responsive" wire:key="tenantcustomersshow">
        {{-- <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
            style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
            <div class="sk-three-bounce" style="background:none;">
                <div class="sk-child sk-bounce1"></div>
                <div class="sk-child sk-bounce2"></div>
                <div class="sk-child sk-bounce3"></div>
            </div>
        </div> --}}
        <div id="dataTables_wrapper" class="dataTables_wrapper">
            <div class="dataTables_length" id="dataTables_length">
                <label>{{ __('Show') }}
                    <select name="perPage" aria-controls="select" wire:model="perPage">
                        <option value="10"
                            @if ($perPage == 10) selected @endif>10</option>
                        <option value="25"
                            @if ($perPage == 25) selected @endif>25</option>
                        <option value="50"
                            @if ($perPage == 50) selected @endif>50</option>
                        <option value="100"
                            @if ($perPage == 100) selected @endif>100</option>
                    </select>
                    {{ __('entries') }}</label>
            </div>
            {{-- <div id="dataTables_search_filter" class="dataTables_filter">
                <label>{{ __('Search') }}:
                    <input type="search" name="searchString" wire:model="searchString"></label>
            </div> --}}
        </div>
        <!-- display dataTable no-footer -->
        <div class="table-responsive w-100">
            <table id="dataTables-data" class="table mb-0 table-striped">
                <thead>
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkAll" required="">
                                <label class="custom-control-label" for="checkAll"></label>
                            </div>
                        </th>
                        <th>{{ __('NIF') }}</th>
                        <th>{{ __('Name') }}</th>
                        {{-- <th>{{ __('Team Member Associated')}}</th> --}}
                        <th>{{ __('Contact') }}</th>
                        <th>{{ __('District') }}</th>
                        <th>Saldo Cliente</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $customer->id }}"
                                        required="">
                                    <label class="custom-control-label" for="customCheckBox{{ $customer->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $customer->nif }}</td>
                            <td>{{ $customer->name }}</td>
                            {{-- <td>{{ $customer->teamMember->name}}</td> --}}
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->state }}</td>
                            <td>{{ $customer->current_account}} €</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary tp-btn-light sharp" type="button" data-toggle="dropdown">
                                        <span class="fs--1">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <circle fill="#000000" cx="5" cy="12" r="2"></circle>
                                                    <circle fill="#000000" cx="12" cy="12" r="2"></circle>
                                                    <circle fill="#000000" cx="19" cy="12" r="2"></circle>
                                                </g>
                                            </svg>
                                        </span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        {{-- @if($customer->account_active == 0)
                                        <a class="dropdown-item" href="{{ route('tenant.loginCustomer.loginCustomer', $customer->id) }}">{{ __('Create Login')}}</a>
                                        @endif --}}
                                        <a class="dropdown-item"
                                            href="{{ route('tenant.customers.edit', $customer->id) }}">{{ __('Edit Customer') }}</a>
                                        
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $customers->links() }}
    </div>
</div>
</div>