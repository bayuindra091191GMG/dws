@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>TAMBAH BARU KATEGORI SAMPAH DWS</h3>
                    </div>
                </div>

                {{ Form::open(['route'=>['admin.dws-wastes.store'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">
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
                                                <label class="form-label" for="title">Kategori</label>
                                                <input id="golongan" type="text" class="form-control"
                                                       name="golongan" value="{{ old('golongan') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="price">Price</label>
                                                <input id="price" type="text" class="form-control"
                                                       name="price" value="{{ old('price') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="img_path">Image (48 x 48)</label><br>
                                                {!! Form::file('img_path', array('id' => 'main_image', 'class' => 'file-loading', 'accept' => 'image/*')) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="description">Description</label>
                                                <textarea name="description" id="description" class="form-control" rows="10" style="display: none;">{{ old('description') }}</textarea>
                                                <div id="editor" style="height: 300px;">{{ old('description') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                    <a href="{{ route('admin.dws-wastes.index') }}" class="btn btn-danger">Exit</a>
                                    <input type="submit" class="btn btn-success" value="Save">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/libs/quill/dist/quill.snow.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/libs/quill/dist/quill.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.1.0"></script>

    <script type="text/javascript">
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
