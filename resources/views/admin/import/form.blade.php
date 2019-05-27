@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>IMPORT FOR DEVELOPER</h3>
                    </div>
                </div>

                {{ Form::open(['route'=>['admin.import.submit'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}

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
                                        @endforeach
                                        <!-- Input -->
                                        <div class="body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="import">Import *</label>
                                                    <select id="import" name="import" class="form-control">
                                                        <option value="1">User</option>
                                                        <option value="2">Transaction</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group form-float form-group-lg">
                                                    <div class="form-line">
                                                        <label class="form-label" for="img_path">EXCEL *</label>
                                                        {!! Form::file('excel', array('id' => 'excel', 'class' => 'file-loading')) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                            <input type="submit" class="btn btn-success" value="IMPORT">
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
@endsection


@section('styles')

@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection