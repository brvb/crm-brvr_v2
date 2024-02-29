<div>
    <style>
         #map {
            height: 500px; 
        }
        .leaflet-control-attribution.leaflet-control {
            display: none;
        }

    </style>
    <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
        style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
        <div class="sk-three-bounce" style="background:none;">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#locationPanel"><i
                    class="flaticon-381-location-2 mr-2"></i> {{ __('Location') }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" id="mapClick" href="#mapPanel"><i
                    class="flaticon-381-location-2 mr-2"></i> Mapa</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="locationPanel">
                <div class="row">
                    <div class="col-12">
                        <div class="card" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="{{ $action }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @if ($update)
                                    @method('PUT')
                                    @endif
                                    <div class="tab-content">
                                        <section class="form-group row" wire:ignore>
                                            <div class="col-xl-12 col-xs-12">
                                                <label>Selecione Cliente</label>
                                                <select name="selectedCustomer" class="form-control" id="selectedCustomer" >
                                                    <option value="">{{ __('Select Customer') }}</option>
                                                    @foreach ($customerList->customers as $cst )
                                                        <option value="{{$cst->no}}" @isset($customerLocation) @if($cst->no == $customerLocation->no) selected @endif @endisset>{{ $cst->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </section>
                                        <section class="form-group row">
                                            <div class="col-xl-10 col-xs-12">
                                                
                                                <label>{{ __('Location Name') }}</label>
                                                <input type="text" name="description" id="description" class="form-control"
                                                    @if(null !==old('description'))value="{{ old('description') }}"
                                                    @endisset placeholder="{{ __('Location Name') }}"
                                                    wire:model.defer="description">
                                            </div>
                                            <div class="col-xl-2 col-xs-12">
                                                <label>{{ __('Main Location') }}</label>
                                                <input type="checkbox" name="main" id="main" style="pointer-events: none;" @isset($customerLocation->locationmainornot) @if($customerLocation->locationmainornot ==
                                                true) checked readonly value="1" @else value="0" @endif @endisset>
                                            </div>
                                        </section>
                                        <section class="form-group row">
                                            <div class="col-xl-3 col-xs-12">
                                                <label>{{ __('Location Phone number') }}</label>
                                                <input type="text" name="contact" id="contact" class="form-control" @if(null
                                                    !==old('contact'))value="{{ old('contact') }}" @endisset
                                                    placeholder="{{ __('Location Phone number') }}"
                                                    wire:model.defer="contact">
                                            </div>
                                            <div class="col-xl-6 col-xs-12">
                                                <label>{{ __('Manager name') }}</label>
                                                <input type="text" name="manager_name" id="manager_name"
                                                    class="form-control" @if(null
                                                    !==old('manager_name'))value="{{ old('manager_name') }}" @endisset
                                                    placeholder="{{ __('Manager name') }}" wire:model.defer="manager_name">
                                            </div>
                                            <div class="col-xl-3 col-xs-12">
                                                <label>{{ __('Manager Phone number') }}</label>
                                                <input type="text" name="manager_contact" id="manager_contact"
                                                    class="form-control" @if(null
                                                    !==old('manager_contact'))value="{{ old('manager_contact') }}" @endisset
                                                    placeholder="{{ __('Manager Phone number') }}"
                                                    wire:model.defer="manager_contact">
                                            </div>
                                        </section>
                                        <section class="form-group row">
                                            <div class="col-12" wire:ignore>
                                                <label>{{ __('Location Address') }}</label>
                                                <input type="text" name="address" id="address" class="form-control" @if(null
                                                    !==old('address'))value="{{ old('address') }}" @endisset
                                                    placeholder="{{ __('Location Address') }}" wire:model.defer="address">
                                            </div>
                                        </section>
                                        <section class="form-group row">
                                            <div class="col-xl-2 col-xs-12">
                                                <label>{{ __('Location Zip Code') }}</label>
                                                <input type="text" name="zipcode" id="zipcode" class="form-control" @if(null
                                                    !==old('zipcode'))value="{{ old('zipcode') }}" @endisset
                                                    placeholder="{{ __('Zip Code') }}" wire:model.defer="zipcode">
                                            </div>

                                            <div class="col-xl-2 col-xs-12">
                                                <label>Distrito</label>
                                                <input type="text" name="district" id="district" class="form-control" @if(null
                                                    !==old('district'))value="{{ old('district') }}" @endisset
                                                    placeholder="Distrito" wire:model.defer="district">
                                            </div>

                                            <div class="col-xl-2 col-xs-12">
                                                <label>Cidade</label>
                                                <input type="text" name="county" id="county" class="form-control" @if(null
                                                    !==old('county'))value="{{ old('county') }}" @endisset
                                                    placeholder="Cidade" wire:model.defer="county">
                                            </div>
                                        
                                        </section>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="mapPanel">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="basic-form">
                                
                                <div id="map"></div><br>
                                @if(isset($latitude))
                                    <span id="latitude" style="display:none;">{{ $latitude }}</span>
                                @endif

                                @if(isset($longitude))
                                 <span id="longitude" style="display:none;">{{ $longitude }}</span>
                                @endif
                                <button type="button" class="btn btn-primary" id="googleMaps">Google Maps</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="card">
            <div class="card-footer justify-content-between">
                <div class="row">
                    <div class="col text-right">
                        @if(Auth::user()->type_user != 2)
                            <a href="{{ route('tenant.customer-locations.index') }}" class="btn btn-secondary mr-2">{{
                                __('Back') }}
                                <span class="btn-icon-right"><i class="las la-angle-double-left"></i></span>
                            </a>
                        @endif
                        <button type="submit" style="border:none;background:none;">
                            <a type="submit" class="btn btn-primary"  role="button">
                                {{ $buttonAction }}
                                <span class="btn-icon-right"><i class="las la-check mr-2"></i></span>
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
        @push('custom-scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    
     
        <script>

            jQuery( document ).ready(function() {

            var startPoint = [41.3729345, -8.7531294];

            //VOU RECEBER DO VINICIUS
            
            if(jQuery("#latitude").text() == 0 && jQuery("#longitude").text() == 0)
            {
                var endPoint = [41.3729345, -8.7531294];
            } else 
            {
                var endPoint = [jQuery("#latitude").text(), jQuery("#longitude").text()];
            }
            

                
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    
                    startPoint = [latitude, longitude];
            
                }, function(error) {
                    startPoint = [41.3729345, -8.7531294];

                });
               
            }
            else
            {
                startPoint = [41.3729345, -8.7531294];
            }

               
                var map = L.map('map').setView(endPoint, 12);

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 25,
                }).addTo(map);

                               

                var marker = L.marker(endPoint).addTo(map)
                .bindPopup(jQuery("#address").val())



                var requestUrl = 'https://api.openrouteservice.org/v2/directions/driving-car?api_key=5b3ce3597851110001cf6248440ce94056d6420bb9f65237e2c92278&start=' + startPoint[1] + ',' + startPoint[0] + '&end=' + endPoint[1] + ',' + endPoint[0] + '&language=pt';
               

                // Envia a solicitação
                fetch(requestUrl)
                    .then(response => response.json())
                    .then(data => {

            
                        var instructions = data.features[0].properties.segments[0].steps.map(step => step.instruction);

                        // Extrai a geometria da rota da resposta
                        var routeGeometry = L.geoJSON(data.features[0].geometry);

                        // Adiciona a geometria da rota ao mapa Leaflet
                        routeGeometry.addTo(map);

                    })
                    .catch(error => console.error('Erro ao calcular a rota:', error));


                    jQuery("body").on("click","#googleMaps",function(){
                        var googleMapsLink = 'https://www.google.com/maps/dir/?api=1&origin=' + startPoint[0] + ',' + startPoint[1] + '&destination=' + endPoint[0] + ',' + endPoint[1];
                        window.open(googleMapsLink, '_blank');
                    });


                    //APANHAR INFO E DELINEAR NO MAPA

                    // fetch('https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/georef-portugal-distrito/records?limit=20')
                    // .then(response => response.json())
                    // .then(data => {
                      
                    //    data.results.forEach(function(element) {   
                        

                    //     if(element.geo_shape.geometry.type == "Polygon")
                    //     {

                    //         var geojsonData = element.geo_shape.geometry.coordinates[0];

                            
                    //         var geojsonData = {
                    //             "type": "Feature",
                    //             "geometry": {
                    //                 "type": "Polygon",
                    //                 "coordinates": [geojsonData]
                    //             },
                    //             "distrito" : {
                    //                 "name": element.dis_name_upper
                    //             }
                    //         };
                
                    //         L.geoJSON(geojsonData, {
                    //             style: {
                    //                 color: 'blue',
                    //                 weight: 1,
                    //                 fillOpacity: 0.2
                    //             }
                    //         }).bindPopup(function(layer) {
                            
                    //                 return "Distrito: "+layer.feature.distrito.name;
                    //         }).addTo(map);

                    //         }
                    //         else
                    //         {
                
                    //             element.geo_shape.geometry.coordinates.forEach(function(elementtt) {  
                                    
                    //                 var geojsonData = elementtt[0];
                                
                    //                 var geojsonData = {
                    //                     "type": "Feature",
                    //                     "geometry": {
                    //                         "type": "Polygon",
                    //                         "coordinates": [geojsonData]
                    //                     },
                    //                     "distrito" : {
                    //                         "name": element.dis_name_upper
                    //                     }
                    //                 };

                            
                    //                 L.geoJSON(geojsonData, {
                    //                     style: {
                    //                         color: 'blue',
                    //                         weight: 1,
                    //                         fillOpacity: 0.2
                    //                     }
                    //                 }).bindPopup(function(layer) {

                    //                         return "Distrito: "+layer.feature.distrito.name;
                    //                 }).addTo(map);

                    //             });


                    //         }
                    //     });
                    // });
                   


                  

              
               
                setInterval(function () {
                map.invalidateSize();
                }, 100);

            });

           
        


             document.addEventListener('livewire:load', function () {

                jQuery('#selectedCustomer').select2();
                jQuery("#selectedCustomer").on("select2:select", function (e) {
                    @this.set('selectedCustomer', jQuery('#selectedCustomer').find(':selected').val(), true)
                });

                jQuery("#selectedCustomer").on("change",function(e)
                {
                    jQuery("#customer_id").val(jQuery(this).val());
                });

            

             });



             jQuery("#main").on("change",function(e)
             {
                if(jQuery(this).is(":checked"))
                {
                    jQuery(this).val("1");
                }
                else {
                    jQuery(this).val("0");
                }
             });

            window.addEventListener('swal',function(e){
                if(e.detail.confirm) {
                    var page = e.detail.page;
                    var customer_id = e.detail.customer_id;
                    swal.fire({
                        title: e.detail.title,
                        html: e.detail.message,
                        type: e.detail.status,
                        page: e.detail.page,
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: e.detail.confirmButtonText,
                        cancelButtonText: e.detail.cancellButtonText})
                    .then((result) => {
                        if(result.value) {
                            Livewire.emit('resetChanges');
                            if(page != "edit")
                            {
                                jQuery("#selectedCustomer").val("");
                            }
                            else {                               
                                jQuery("#selectedCustomer").val(jQuery("#selectedCustomer").attr('selected','selected'));
                            }
                        }
                    });
                } else {
                    swal(e.detail.title, e.detail.message, e.detail.status);
                    jQuery("#customer_id").val(jQuery("#selectedCustomer").val());
                }
            });
        </script>
        @endpush


    
