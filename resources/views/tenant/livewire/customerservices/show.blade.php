<div>
<div class="container-fluid" wire:key="tenantcustomerservicesshowservices">
    <div class="row">
        <div class="col-12 col-sm-6 teste-left text-center d-sm-flex justify-content-start" >
            <h4 class="card-title">{{ __('Customer Services') }}</h4>
        </div>
        
        <div class="col-12 col-sm-6 teste-right text-center d-sm-flex justify-content-end">
            <a href="{{ route('tenant.services.create') }}" class="btn btn-primary">{{ __('Add Customer Service') }}</a>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="table-responsive">
        <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
            style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
            <div class="sk-three-bounce" style="background:none;">
                <div class="sk-child sk-bounce1"></div>
                <div class="sk-child sk-bounce2"></div>
                <div class="sk-child sk-bounce3"></div>
            </div>
        </div>
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
            <div id="dataTables_search_filter" class="dataTables_filter">
                <label>{{ __('Search') }}:
                    <input type="search" name="searchString" wire:model="searchString"></label>
            </div>
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
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Customer Location')}}</th>
                        <th>{{ __('Service') }}</th>
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customerServices as $customer)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheckBox{{ $customer->id }}"
                                        required="">
                                    <label class="custom-control-label" for="customCheckBox{{ $customer->id }}"></label>
                                </div>
                            </td>
                            <td>{{ $customer->customer->name }}</td>
                            <td>{{ $customer->customerLocation->description }}</td>
                            <td>{{ $customer->service->name }}</td>
                            <td>{{ $customer->start_date }}</td>
                            <td>{{ $customer->type }}</td>
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
                                        <a class="dropdown-item"
                                            href="{{ route('tenant.services.edit', $customer->id) }}">{{ __('Edit Customer Service') }}</a>
                                            <button class="dropdown-item btn-sweet-alert" data-type="form"
                                                data-route="{{ route('tenant.services.destroy', $customer->id) }}"
                                                data-style="warning" data-csrf="csrf"
                                                data-text="{{ __('Do you want to delete this customer service?') }}"
                                                data-title="{{ __('Are you sure?') }}"
                                                data-btn-cancel="{{ __('No, cancel it!!') }}"
                                                data-btn-ok="{{ __('Yes, delete it!!') }}" data-method="DELETE">
                                                {{ __('Delete Customer Service') }}
                                            </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $customerServices->links() }}
    </div>
</div>
</div>