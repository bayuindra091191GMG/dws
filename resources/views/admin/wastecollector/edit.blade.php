@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Ubah Data Waste Collector</h2>

                {{ Form::open(['route'=>['admin.wastecollectors.update'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
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
                                                            @if($wasteCollector->status_id == 2)
                                                                <option value="2" selected>Not Active</option>
                                                            @else
                                                                <option value="2">Not Active</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <input id="id" type="hidden"
                                                       name="id" value="{{ $wasteCollector->id }}"/>
                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="email">Email *</label>
                                                            <input id="email" type="email" class="form-control"
                                                                   name="email" value="{{ $wasteCollector->email }}"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="first_name">Nama Depan *</label>
                                                            <input id="first_name" type="text" class="form-control"
                                                                   name="first_name" value="{{ $wasteCollector->first_name }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="last_name">Nama Belakang *</label>
                                                            <input id="last_name" type="text" class="form-control"
                                                                   name="last_name" value="{{ $wasteCollector->last_name }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="identity_number">No Identitas *</label>
                                                            <input id="identity_number" type="text" class="form-control"
                                                                   name="identity_number" value="{{ $wasteCollector->identity_number }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="phone">No Handphone *</label>
                                                            <input id="phone" type="text" class="form-control"
                                                                   name="phone" value="{{ $wasteCollector->phone }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="address">Alamat *</label>
                                                            <textarea id="address" class="form-control"
                                                                      name="address">{{ $wasteCollector->address }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="wastebank">Waste Processor *</label>
                                                            <select id="wastebank" name="wastebank" class="form-control">
                                                                <option value="-1" @if($wasteBankId == -1) selected @endif> - Pilih Waste Processor - </option>
                                                                @foreach($wasteBanks as $wasteBank)
                                                                    <option value="{{ $wasteBank->id }}" @if($wasteBankId === $wasteBank->id) selected @endif>{{ $wasteBank->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="img_path">Ganti Foto</label>
                                                            {!! Form::file('img_path', array('id' => 'main_image', 'class' => 'file-loading', 'accept' => 'image/*')) !!}
                                                            <img src="{{ asset('storage/admin/wastecollector/'. $wasteCollector->img_path) }}" style="height: 200px; width: auto;"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                <a href="{{ route('admin.wastecollectors.index') }}" class="btn btn-danger">BATAL</a>
                                                <input type="submit" class="btn btn-success" value="SIMPAN">
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

@section('styles')
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet"/>
    <style>
        .select2-container--default .select2-search--dropdown::before {
            content: "";
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript">
        $('#wastebank').select2();
    </script>
@endsection