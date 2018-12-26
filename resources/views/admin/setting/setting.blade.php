@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Edit Admin Users</h2>

                {{ Form::open(['route'=>['admin.setting.update'],'method' => 'post','id' => 'general-form']) }}
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body b-b">
                                    <div class="tab-content pb-3" id="v-pills-tabContent">
                                        <div class="tab-pane animated fadeInUpShort show active" id="v-pills-1">
                                            <!-- Input -->
                                            <div class="body">
                                                <div class="col-sm-12">
                                                    <div class="form-check mb-2 mr-sm-2">
                                                        @if($configuration->configuration_value == 1)
                                                            <input type="checkbox" id="is_masaro" name="is_masaro" value="true" class="form-check-input" checked/>
                                                        @else
                                                            <input type="checkbox" id="is_masaro" name="is_masaro" value="true" class="form-check-input"/>
                                                        @endif
                                                        <label class="form-check-label" for="is_masaro">
                                                            Masaro
                                                        </label>
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