<div>
    <style type="text/css">
        #map_canvas {height:400px;width:100%}
    </style>
    <div id="map_canvas"></div>
    <br>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="new-venue-form" method="post" action="/venues/create">
        <div>
            <label>Venue Name</label><input class="col-sm-12" type="text" name="venue-name" placeholder="Venue Name" />
        </div>
        <div>
            <label>Venue Contact</label><input class="col-sm-12" type="text" name="contact-name" placeholder="Contact Name" />
        </div>
        <div>
            <label>Venue Email</label><input class="col-sm-12" type="text" name="contact-email" placeholder="Contact Email" />
        </div>
        <div>
            <label>Venue Number</label><input class="col-sm-12" type="text" name="contact-telephone" placeholder="Contact Telephone" />
        </div>
        <div>
            <label>Latitude</label><input type="text" id="latFld" name="latitude" />
        </div>
        <div>
            <label>Longitude</label><input type="text" id="lngFld" name="longitude" />
        </div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="btn">Create new Venue</button>
    </form>




    <script type="text/javascript">
        var map;
        var markersArray = [];

        function initMap()
        {
            var latlng = new google.maps.LatLng(51.501416, -0.144031);
            var myOptions = {
                zoom: 10,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

            // add a click event handler to the map object
            google.maps.event.addListener(map, "click", function(event)
            {
                // place a marker
                placeMarker(event.latLng);

                // display the lat/lng in your form's lat/lng fields
                document.getElementById("latFld").value = event.latLng.lat();
                document.getElementById("lngFld").value = event.latLng.lng();
            });
        }
        function placeMarker(location) {
            // first remove all markers if there are any
            deleteOverlays();

            var marker = new google.maps.Marker({
                position: location,
                map: map
            });

            // add marker in markers array
            markersArray.push(marker);

            //map.setCenter(location);
        }

        // Deletes all markers in the array by removing references to them
        function deleteOverlays() {
            if (markersArray) {
                for (i in markersArray) {
                    markersArray[i].setMap(null);
                }
                markersArray.length = 0;
            }
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDf2-w-MeO0DQexjZU8516ZRZ2XUb53S7M&callback=initMap"></script>
</div>
