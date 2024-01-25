<x-tenant.setup.zones.form :action="route('tenant.setup.zones.store')"
    :update="false"
    :zonesList="$zonesList"
    :name="$name"
    :locals="$locals"
    :comercial="$comercial"
    buttonAction="{{ __('Create Zone') }}" formTitle="{{ __('Create Zone') }}" />
