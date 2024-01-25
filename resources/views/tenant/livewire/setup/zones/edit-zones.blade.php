
<x-tenant.setup.zones.form
    :action="route('tenant.setup.zones.store')"
    :name="$name"
    :locals="$locals"
    :comercial="$comercial"
    :update="true" 
    buttonAction="{{ __('Update Zone') }}"
    formTitle="{{ __('Update Zone') }}"/>
