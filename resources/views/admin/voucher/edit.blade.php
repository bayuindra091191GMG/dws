@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Ubah Data Voucher</h2>
                {{ Form::open(['route'=>['admin.vouchers.update'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
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
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="qty">Quantity *</label>
                                                            <input id="qty" type="text" class="form-control"
                                                                   name="qty" value="{{ $voucher->quantity }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="required_point">Required Point *</label>
                                                            <input id="required_point" type="text" class="form-control"
                                                                   name="required_point" value="{{ $voucher->required_point }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="company">Company *</label>
                                                        <select id="company" name="company" class="form-control">
                                                            @if($voucher->company_id != null)
                                                                <option value="{{ $voucher->company_id }}">{{ $voucher->company->name }}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="category">Category *</label>
                                                        <select id="category" name="category" class="form-control">
                                                            @if($voucher->category_id != null)
                                                                <option value="{{ $voucher->category_id }}">{{ $voucher->voucher_category->name }}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="affiliate">Affiliate *</label>
                                                        <select id="affiliate" name="affiliate" class="form-control">
                                                            @if($voucher->affiliate_id != null)
                                                                <option value="{{ $voucher->affiliate_id }}">{{ $voucher->affiliate->name }}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group form-float form-group-lg">
                                                        <div class="form-line">
                                                            <label class="form-label" for="img_path">Image *</label>
                                                            <br/>
                                                            <img src="{{ asset('storage/admin/vouchers/'.$voucher->img_path) }}" alt="voucherPicture" width="400px"/>
                                                            {!! Form::file('img_path', array('id' => 'main_image', 'class' => 'file-loading form-control', 'accept' => 'image/*')) !!}
                                                        </div>
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
                url: '{{ route('select.voucher-categories') }}',
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

        $('#affiliate').select2({
            placeholder: {
                id: '-1',
                text: 'Choose Affiliate...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.affiliates') }}',
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

        $('#company').select2({
            placeholder: {
                id: '-1',
                text: 'Choose Company...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.companies') }}',
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