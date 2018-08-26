@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Analytics</div>

                    <div class="card-body">
                        {{--@if (session('status'))--}}
                        {{--<div class="alert alert-success" role="alert">--}}
                        {{--{{ session('status') }}--}}
                        {{--</div>--}}
                        {{--@endif--}}

                        {{--You are logged in!--}}
                        <div>
                            <b>Short URL:</b>
                            <span><a href="{{ url('/').'/'.$link->url_short }}">{{ url('/').'/'.$link->url_short }}</a></span>
                        </div>
                        <div>Visits:</div>
                        <table>
                            <tr>
                                <th>#</th>
                                <th>Country</th>
                                <th>OS</th>
                                <th>Browser</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                            </tr>
                            @foreach($link->visits as $i => $visit)

                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $visit->country }}</td>
                                    <td>{{ $visit->os }}</td>
                                    <td>{{ $visit->browser }}</td>
                                    <td>{{ $visit->long }}</td>
                                    <td>{{ $visit->lat }}</td>
                                </tr>
                            @endforeach
                        </table>
                        <br>

                        <div id="map" style="width: 100%; height: 400px;"></div>

                        <script type="text/javascript">
                            function initMap() {

                                var loc = <?php echo json_encode($link->visits) ?>;
                                locations = [];
                                for (i = 0; i < loc.length; i++) {
                                    locations.push(['Marker name', Number(loc[i].lat), Number(loc[i].long)]);
                                }

                                var map = new google.maps.Map(document.getElementById('map'), {
                                    center: {lat: 50.431782, lng: 30.516382},
                                    zoom: 8,
                                    // center: new google.maps.LatLng(50.431782,30.516382),
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                });

                                var infowindow = new google.maps.InfoWindow();
                                var marker, i;

                                for (i = 0; i < locations.length; i++) {
                                    marker = new google.maps.Marker({
                                        // position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                        position: {'lat':locations[i][1], 'lng':locations[i][2]},
                                        map: map,
                                        // icon: {
                                        //     url: locations[i][3],
                                        //     scaledSize: new google.maps.Size(34, 38)
                                        // }
                                    });
                                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                        return function() {
                                            infowindow.setContent(locations[i][0]);
                                            infowindow.open(map, marker);
                                        }
                                    })(marker, i));
                                }
                            }
                        </script>
                        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_API') }}&callback=initMap" async defer></script>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
