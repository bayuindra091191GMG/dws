@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>UBAH JARAK RADIUS PENGOLAHAN SAMPAH</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        {{ Form::open(['route'=>['admin.setting-wastebank.update'],'method' => 'post','id' => 'general-form']) }}

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body b-b">
                                        <div class="tab-content pb-3" id="v-pills-tabContent">
                                            <div class="tab-pane animated fadeInUpShort show active" id="v-pills-1">
                                                <!-- Input -->
                                                <div class="body">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-float form-group-lg">
                                                            <div class="form-line">
                                                                <label class="form-label" for="code">Radius Pengolahan Sampah (km)</label>
                                                                <input id="wastebank_radius" name="wastebank_radius" type="text" class="form-control" value="{{ $configuration->configuration_value }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                    <input type="submit" class="btn btn-success" value="SIMPAN">
                                                </div>
                                                <!-- #END# Input -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection