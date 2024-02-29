<x-tenant.customerlocations.form
    :action="route('tenant.customer-locations.update',$id_customerLocation->id)"
    :update="true"
    :customerList="$customerList"
    :customerLocation="$id_customerLocation"
    :latitude="$latitude"
    :longitude="$longitude"
    :districts="$districts"
    :counties="$counties"
    :zipcode="$zipcode"
    :district="$district"
    :county="$county"
    cancelButton="{{ __('No, cancel update') }}"
    buttonAction="{{ __('Yes, update') }}"
    formTitle="Atualizar Localização de Cliente" />

   