@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Buat Waste Collector</h2>

            {{ Form::open(['route'=>['admin.wastecollectors.store'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
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
                                            @endforeach
                                            <!-- Input -->
                                            <div class="body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="status">Status *</label>
                                                        <select id="status" name="status" class="form-control">
                                                            <option value="1">Active</option>
                                                            <option value="2">Not Active</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="email">Email *</label>
                                                            <input id="email" type="email" class="form-control"
                                                                   name="email" value="{{ old('email') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="password">Password *</label>
                                                            <input id="password" type="password" class="form-control"
                                                                   name="password">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="password_confirmation">Konfirmasi Password *</label>
                                                            <input id="password_confirmation" type="password" class="form-control"
                                                                   name="password_confirmation">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="first_name">Nama Depan *</label>
                                                            <input id="first_name" type="text" class="form-control"
                                                                   name="first_name" value="{{ old('first_name') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="last_name">Nama Belakang *</label>
                                                            <input id="last_name" type="text" class="form-control"
                                                                   name="last_name" value="{{ old('last_name') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="identity_number">No Identitas *</label>
                                                            <input id="identity_number" type="text" class="form-control"
                                                                   name="identity_number" value="{{ old('identity_number') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="phone">No Handphone *</label>
                                                            <input id="phone" type="text" class="form-control"
                                                                   name="phone" value="{{ old('phone_number') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="address">Alamat *</label>
                                                            <textarea id="address" class="form-control"
                                                                      name="address">{{ old('address') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="img_path">Foto *</label>
                                                            {!! Form::file('img_path', array('id' => 'main_image', 'class' => 'file-loading', 'accept' => 'image/*')) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                <a href="{{ route('admin.wastecollectors.index') }}" class="btn btn-danger">Exit</a>
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