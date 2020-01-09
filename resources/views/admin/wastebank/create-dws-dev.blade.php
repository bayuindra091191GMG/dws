@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>TAMBAH BARU PENGOLAHAN SAMPAH KATEGORI DWS</h3>
                    </div>
                </div>

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
                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="home" aria-selected="true">Basic Info</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="profile" aria-selected="false">Schedules</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                                            <div class="col-md-12 p-t-20">
                                                                <div class="form-group form-float form-group-lg">
                                                                    <div class="form-line">
                                                                        <label class="form-label" for="name">Name *</label>
                                                                        <input id="name" type="text" class="form-control"
                                                                               name="name" value="{{ old('name') }}">
                                                                        <input type="hidden" name="categoryType" value="1" />
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

{{--                                                            <div class="col-md-12">--}}
{{--                                                                <div class="form-group form-float form-group-lg">--}}
{{--                                                                    <div class="form-line">--}}
{{--                                                                        <label class="form-label" for="senin">Open Day</label>--}}
{{--                                                                        <input type="checkbox" name="days[]" id="senin" value="1"/> Senin--}}
{{--                                                                        <input type="checkbox" name="days[]" id="selasa" value="2"/> Selasa--}}
{{--                                                                        <input type="checkbox" name="days[]" id="rabu" value="3"/> Rabu--}}
{{--                                                                        <input type="checkbox" name="days[]" id="kamis" value="4"/> Kamis--}}
{{--                                                                        <input type="checkbox" name="days[]" id="jumat" value="5"/> Jumat--}}
{{--                                                                        <input type="checkbox" name="days[]" id="sabtu" value="6"/> Sabtu--}}
{{--                                                                        <input type="checkbox" name="days[]" id="minggu" value="7"/> Minggu--}}
{{--                                                                        <input type="checkbox" id="selectAllDay" onchange="selectAll()"/> Select/Unselect All--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}

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
                                                        <div class="tab-pane fade" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
                                                            <div class="col-md-12 p-t-20">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-hover" id="tab_logic">
                                                                        <thead>
                                                                        <tr >
                                                                            <th class="text-center" style="width: 30%">
                                                                                Hari
                                                                            </th>
                                                                            <th class="text-center" style="width: 30%">
                                                                                Jam
                                                                            </th>
                                                                            <th class="text-center" style="width: 30%">
                                                                                Dws Kategori
                                                                            </th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <tr id='sch0'>
                                                                            <td class='field-item'>
                                                                                <select class="form-control" id="schDay0" name="schDays[]">
                                                                                    <option value="1">Senin</option>
                                                                                    <option value="2">Selasa</option>
                                                                                    <option value="3">Rabu</option>
                                                                                    <option value="4">Kamis</option>
                                                                                    <option value="5">Jumat</option>
                                                                                    <option value="6">Sabtu</option>
                                                                                    <option value="7">Minggu</option>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input id="schTime0" type="text" class="form-control time-inputmask"
                                                                                       name="schTimes[]"/>
                                                                            </td>
                                                                            <td>
                                                                                <select id="select0" name="dwsTypes[]" class='form-control'></select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr id='sch1'></tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <a id="add_row" class="btn btn-success">Tambah</a><a id='delete_row' class="btn btn-danger">Hapus</a>
                                                            </div>
                                                        </div>
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
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRY4YnVU5GsEyGTYsP9fq9zLo1AqBe1Js&libraries=places"
            type="text/javascript"></script>

    <script type="text/javascript">

        $('#city').select2();

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

        $('#select0').select2({
            placeholder: {
                id: '-1',
                text: ' - Pilih DWS Kategori - '
            },
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route('select.dws-categories') }}',
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

        var i=1;
        $("#add_row").click(function(){
            $('#sch'+i).html("<td class='field-item'>" +
                "<select class='form-control' id='schDay0' name='schDays[]'>" +
                "<option value='1'>Senin</option>" +
                "<option value='2'>Selasa</option>" +
                "<option value='3'>Rabu</option>" +
                "<option value='4'>Kamis</option>" +
                "<option value='5'>Jumat</option>" +
                "<option value='6'>Sabtu</option>" +
                "<option value='7'>Minggu</option>" +
                "</select>" +
                "</td><td><input type='text' id='schTime" + i + "' name='schTimes[]' class='form-control time-inputmask'></td>" +
                "<td><select id='select" + i +"' name='dwsTypes[]' class='form-control'></select></td>");

            $('#tab_logic').append('<tr id="sch'+(i+1)+'"></tr>');

            $(".time-inputmask").inputmask("hh:mm", {
                placeholder: "HH:MM",
                insertMode: false,
                showMaskOnHover: false
            });

            $('#select' + i).select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih DWS Kategori - '
                },
                width: '100%',
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('select.dws-categories') }}',
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

            i++;
        });

        $("#delete_row").click(function(){
            if(i>1){
                $("#sch"+(i-1)).html('');
                i--;
            }
        });
    </script>
@endsection
