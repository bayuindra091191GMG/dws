@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Tambah Baru Kategori Sampah Masaro</h2>
                {{ Form::open(['route'=>['admin.masaro-wastes.store'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
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
                                            @endforeach

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
                                                            <label class="form-label" for="price">Price *</label>
                                                            <input id="price" type="text" class="form-control"
                                                                   name="price" value="{{ old('price') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="img_path">Image (120 x 120)</label><br>
                                                            {!! Form::file('img_path', array('id' => 'main_image', 'class' => 'file-loading', 'accept' => 'image/*')) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="img_path">Contoh Image (satu atau banyak)</label><br>
                                                            {{--                                                {!! Form::file('example_path[]', array('id' => 'main_image', 'class' => 'file-loading', 'accept' => 'image/*')) !!}--}}
                                                            <input required type="file" id="example_path" class="file-loading" name="example_path[]" accept="image/*" multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="description">Contoh *</label>
                                                            <input type="text" name="example" id="example" class="form-control" value="{{ old('example') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="description">Deskripsi *</label>
                                                            <textarea name="description" id="description" class="form-control" rows="10" style="display: none;">{{ old('description') }}</textarea>
                                                            <div id="editor" style="height: 300px;">{{ old('description') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                <a href="{{ route('admin.masaro-wastes.index') }}" class="btn btn-danger">Exit</a>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/libs/quill/dist/quill.snow.css') }}">
    <link href="{{ asset('backend/assets/libs/bootstrap-fileinput/fileinput.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('backend/assets/libs/quill/dist/quill.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.1.0"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/libs/bootstrap-fileinput/fileinput.js') }}"></script>
    <script type="text/javascript">
        $("#main_image").fileinput({
            allowedFileExtensions: ["jpg", "jpeg", "png"],
            showUpload: false,
        });

        $("#example_path").fileinput({
            allowedFileExtensions: ["jpg", "jpeg", "png"],
            showUpload: false,
        });

        var quill = new Quill('#editor', {
            theme: 'snow'
        });

        $('#general-form').submit(function() {
            $('#description').val(quill.root.innerHTML);

            return true; // return false to cancel form action
        });

        // Add autonumeric
        priceFormat = new AutoNumeric('#price', {
            minimumValue: '0',
            digitGroupSeparator: '',
            decimalPlaces: 0,
            modifyValueOnWheel: false
        });
    </script>
@endsection
