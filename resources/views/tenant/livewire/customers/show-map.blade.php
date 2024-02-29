<div>
<style>
         #map {
            height: 500px; 
        }
        .leaflet-control-attribution.leaflet-control {
            display: none;
        }

</style>

<div class="table-responsive" wire:key="tenantcustomersshow">
    <div id="ajaxLoading" wire:loading.flex class="w-100 h-100 flex "
        style="background:rgba(255, 255, 255, 0.8);z-index:999;position:fixed;top:0;left:0;align-items: center;justify-content: center;">
        <div class="sk-three-bounce" style="background:none;">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
   
    <div>
        <div id="map"></div>
        <span id="registo" style="display:none;">{{$customerLocations}}</span>
    </div>

</div>




@push('custom-scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
crossorigin=""></script>
<script>

jQuery( document ).ready(function() {

        var locais = JSON.parse(jQuery("#registo").text());
        
        var startPoint = [39.3729345, -8.7531294];
            
        var map = L.map('map').setView(startPoint, 6);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 25,
        }).addTo(map);
    

        locais.locations.forEach(function(element) {

            var endPoint = [element.latitude, element.longitude];

            console.log(endPoint);
    
            var marker = L.marker(endPoint).addTo(map)
            .bindPopup(element.address)

        });

        setInterval(function () {
                map.invalidateSize();
        }, 100);
                                 

   
});
</script>
@endpush
</div>