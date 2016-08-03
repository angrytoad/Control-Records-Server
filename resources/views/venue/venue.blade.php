@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$venue->venue_name}}</div>
                    <div class="panel-body">
                        <div class="col-sm-12 col-lg-6">
                            <h4>Edit Venue Details</h4>
                            <style type="text/css">
                                #map_canvas {height:400px;width:100%}
                            </style>
                            <div id="map_canvas"></div>
                            <br>
                            <form id="edit-venue-form" method="post" action="/venues/{{$venue->id}}/edit">
                                <div>
                                    <label>Venue Name</label><input class="col-sm-12" type="text" name="venue-name" placeholder="Venue Name" value="{{$venue->venue_name}}" />
                                </div>
                                <div>
                                    <label>Venue Contact</label><input class="col-sm-12" type="text" name="contact-name" placeholder="Contact Name" value="{{$venue->contact_name}}"/>
                                </div>
                                <div>
                                    <label>Venue Email</label><input class="col-sm-12" type="text" name="contact-email" placeholder="Contact Email"  value="{{$venue->contact_email}}"/>
                                </div>
                                <div>
                                    <label>Venue Telephone</label><input class="col-sm-12" type="text" name="contact-telephone" placeholder="Contact Telephone" value="{{$venue->contact_telephone}}"/>
                                </div>
                                <div>
                                    <label>Latitude</label><input class="col-sm-12" type="text" id="latFld" name="latitude" value="{{json_decode($venue->coordinates)->latitude}}"/>
                                </div>
                                <div>
                                    <label>Longitude</label><input class="col-sm-12" type="text" id="lngFld" name="longitude" value="{{json_decode($venue->coordinates)->longitude}}"/>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn">Edit Venue</button>
                            </form>




                            <script type="text/javascript">
                                var map;
                                var markersArray = [];

                                function initMap()
                                {
                                    var latlng = new google.maps.LatLng("{{json_decode($venue->coordinates)->latitude}}", "{{json_decode($venue->coordinates)->longitude}}");
                                    var myOptions = {
                                        zoom: 13,
                                        center: latlng,
                                        mapTypeId: google.maps.MapTypeId.ROADMAP
                                    };
                                    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

                                    placeMarker(latlng);

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
                        <div class="col-sm-12 col-md-6">
                            <h4>Venue Information</h4>
                            <div>
                                <p><strong>Name: </strong>{{$venue->venue_name}}</p>
                                <p><strong>Primary Contact: </strong>{{$venue->contact_name}}</p>
                                <p><strong>Primary Email: </strong>{{$venue->contact_email}}</p>
                                <p><strong>Primary Telephone: </strong>{{$venue->contact_telephone}}</p>
                                <img class="venue-static-image" src="http://maps.googleapis.com/maps/api/staticmap?center={{json_decode($venue->coordinates)->latitude}},{{json_decode($venue->coordinates)->longitude}}&zoom=15&scale=1&size=600x250&maptype=roadmap&key=AIzaSyDf2-w-MeO0DQexjZU8516ZRZ2XUb53S7M&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0x15ea55%7Clabel:%7C{{json_decode($venue->coordinates)->latitude}},{{json_decode($venue->coordinates)->longitude}}"/>
                            </div>
                            <div>
                                <button class="btn btn-danger" onClick="venueOptions.deleteVenue({{$venue->id}})">Delete This Venue</button>
                                <a href="http://ctrl-records.com/venue/{{$venue->id}}" target="_blank"><button class="btn btn-info">View Venue Page</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var venueOptions = {
            deleteVenue: function($id){
                swal({
                    title: 'Are you sure?',
                    text: "This will also remove any gigs currently linked to this venue permanently",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function() {
                    window.location.href = location.pathname+'/delete';
                })
            }
        };
    </script>
@endsection

