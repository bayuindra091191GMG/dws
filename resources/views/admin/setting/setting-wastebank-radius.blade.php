@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Edit Wastebank Radius</h2>

                {{ Form::open(['route'=>['admin.setting-wastebank.update'],'method' => 'post','id' => 'general-form']) }}
                <div class="container-fluid relative animatedParent animateOnce">
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
                                                            <label class="form-label" for="code">Radius Wastebank (km)</label>
                                                            <input id="wastebank_radius" name="wastebank_radius" type="text" class="form-control" value="{{ $configuration->configuration_value }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Exit</a>
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
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection