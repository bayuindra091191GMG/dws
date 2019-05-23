@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Ubah data Kategori Voucher</h2>

                {{ Form::open(['route'=>['admin.voucher-categories.update'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
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

                                            <div class="body">
                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="code">Nama *</label>
                                                            <input id="name" type="text" class="form-control"
                                                                   name="name" value="{{ $voucherCategory->name }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="img_path">Image *</label>
                                                            <br/>
                                                            <img src="{{ asset('storage/admin/vouchercategories/' . $voucherCategory->img_path) }}" width="400px" alt="categoryPicture" style="margin-bottom: 10px;">
                                                            {!! Form::file('img_path', array('id' => 'main_image', 'class' => 'file-loading form-control', 'accept' => 'image/*')) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                <a href="{{ route('admin.voucher-categories.index') }}" class="btn btn-danger">Exit</a>
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