@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Ubah Data Voucher</h2>
                {{ Form::open(['route'=>['admin.vouchers.update'],'method' => 'post','id' => 'general-form']) }}
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
                                        <!-- Input -->
                                            <div class="body">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="status">Status *</label>
                                                        <select id="status" name="status" class="form-control">
                                                            @if($voucher->status_id == 1)
                                                                <option value="1" selected>Active</option>
                                                                <option value="2">Not Active</option>
                                                            @else
                                                                <option value="1">Active</option>
                                                                <option value="2" selected>Not Active</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="code">Code *</label>
                                                            <input id="code" type="text" class="form-control"
                                                                   name="code" value="{{ $voucher->code }}">
                                                            <input type="hidden" value="{{ $voucher->id }}" name="id">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="description">Description *</label>
                                                            <textarea id="description" type="description" class="form-control"
                                                                      name="description">{{ $voucher->description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="category">Category *</label>
                                                        <select id="category" name="category" class="form-control">
                                                            @if($category != null)
                                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="product">Product *</label>
                                                        <select id="product" name="product" class="form-control">
                                                            @if($product != null)
                                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="start_date">Start Date *</label>
                                                            <input id="start_date" name="start_date" type="text" class="form-control" value="{{ $startDate }}"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="finish_date">Finish Date *</label>
                                                            <input id="finish_date" type="text" class="form-control"
                                                                   name="finish_date" value="{{ $finishDate }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-danger">Exit</a>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .select2-container--default .select2-search--dropdown::before {
            content: "";
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('backend/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        jQuery('#start_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd M yyyy"
        });

        jQuery('#finish_date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd M yyyy"
        });

        $('#category').select2({
            placeholder: {
                id: '-1',
                text: 'Choose Category...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.categories') }}',
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

        $('#product').select2({
            placeholder: {
                id: '-1',
                text: 'Choose Product...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.products') }}',
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
    </script>
@endsection