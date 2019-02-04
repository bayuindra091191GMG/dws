@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Create New Waste Bank</h2>

                {{ Form::open(['route'=>['admin.waste-banks.store'],'method' => 'post','id' => 'general-form']) }}
                {{--<form method="POST" action="{{ route('admin-users.store') }}">--}}
                {{--{{ csrf_field() }}--}}
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body b-b">
                                    <div class="tab-content pb-3" id="v-pills-tabContent">
                                        <div class="tab-pane animated fadeInUpShort show active" id="v-pills-1">
                                            @include('partials.admin._messages')
                                            @foreach($errors->all() as $error)
                                                <ul>
                                                    <li>
                                                    <span class="help-block">
                                                        <strong style="color: #ff3d00;"> {{ $error }} </strong>
                                                    </span>
                                                    </li>
                                                </ul>
                                            @endForeach
                                        <!-- Input -->
                                            <div class="body">

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="name">Name *</label>
                                                            <input id="name" type="text" class="form-control"
                                                                   name="name" value="{{ old('name') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="phone">Phone *</label>
                                                            <input id="phone" type="text" class="form-control"
                                                                   name="phone" value="{{ old('phone') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="address">Address *</label>
                                                            <textarea name="address" id="address" class="form-control" rows="10">{{ old('address') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="searchmap">Location *</label>
                                                            <input type="text" name="location" id="searchmap" class="form-control"/>
                                                        </div>
                                                        <div id="map-canvas" style="height: 200px;"></div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="latitude">Latitude</label>
                                                            <input type="text" name="latitude" id="latitude" class="form-control" readonly/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="longitude">Longitude</label>
                                                            <input type="text" name="longitude" id="longitude" class="form-control" readonly/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="senin">Open Day</label>
                                                            <input type="checkbox" name="days[]" id="senin" value="Senin"/> Senin
                                                            <input type="checkbox" name="days[]" id="selasa" value="Selasa"/> Selasa
                                                            <input type="checkbox" name="days[]" id="rabu" value="Rabu"/> Rabu
                                                            <input type="checkbox" name="days[]" id="kamis" value="Kamis"/> Kamis
                                                            <input type="checkbox" name="days[]" id="jumat" value="Jumat"/> Jumat
                                                            <input type="checkbox" name="days[]" id="sabtu" value="Sabtu"/> Sabtu
                                                            <input type="checkbox" name="days[]" id="minggu" value="Minggu"/> Minggu
                                                            <input type="checkbox" id="selectAllDay" onchange="selectAll()"/> Select/Unselect All
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="open_hours">Open Time</label>
                                                            <input type="text" name="open_hours" id="open_hours" class="form-control time-inputmask"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="closed_hours">Closed Time</label>
                                                            <input type="text" name="closed_hours" id="closed_hours" class="form-control time-inputmask"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="pic">PIC *</label>
                                                        <select id="pic" name="pic" class="form-control"></select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="city">City *</label>
                                                        <select id="city" name="city" class="form-control">
                                                            @foreach($cities as $city)
                                                                @if($city->id == 152)
                                                                    <option value="{{ $city->id }}" selected>{{ $city->name }}</option>
                                                                @else
                                                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                <a href="{{ route('admin.waste-banks.index') }}" class="btn btn-danger">Exit</a>
                                                <input type="submit" class="btn btn-success" value="Save">
                                            </div>
                                            <!-- #END# Input -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--</form>--}}
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqhoPugts6VVh4RvBuAvkRqBz7yhdpKnQ&libraries=places"
            type="text/javascript"></script>

    <script type="text/javascript">
        $('#pic').select2({
            placeholder: {
                id: '-1',
                text: 'Choose Pic...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.admin-users') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });

        var map = new google.maps.Map(document.getElementById('map-canvas'), {
            center:{
                lat: -6.180495,
                lng: 106.82834149999996
            },
            zoom: 15
        });

        var marker = new google.maps.Marker({
            position:{
                lat: -6.180495,
                lng: 106.82834149999996
            },
            map: map,
            draggable: true
        });

        var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'));

        google.maps.event.addListener(searchBox, 'places_changed', function(){
            var places = searchBox.getPlaces();
            var bounds = new google.maps.LatLngBounds();
            var i, place;

            for(i=0; place=places[i]; i++){
                bounds.extend(place.geometry.location);
                marker.setPosition(place.geometry.location);
            }

            map.fitBounds(bounds);
            map.setZoom(15);
        });

        google.maps.event.addListener(marker, 'position_changed', function(){
            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();

            $('#latitude').val(lat);
            $('#longitude').val(lng);
        });

        function selectAll(){
            if(document.getElementById("selectAllDay").checked){
                document.getElementById("senin").checked = true;
                document.getElementById("selasa").checked = true;
                document.getElementById("rabu").checked = true;
                document.getElementById("kamis").checked = true;
                document.getElementById("jumat").checked = true;
                document.getElementById("sabtu").checked = true;
                document.getElementById("minggu").checked = true;
            }
            else{
                document.getElementById("senin").checked = false;
                document.getElementById("selasa").checked = false;
                document.getElementById("rabu").checked = false;
                document.getElementById("kamis").checked = false;
                document.getElementById("jumat").checked = false;
                document.getElementById("sabtu").checked = false;
                document.getElementById("minggu").checked = false;
            }
        }
    </script>
@endsection