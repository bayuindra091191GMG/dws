@extends('layouts.admin')

@section('content')

    <header class="blue accent-3 relative">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4>
                        <i class="icon-package"></i>
                        Edit Voucher
                    </h4>
                </div>
            </div>
        </div>
    </header>

    {{ Form::open(['route'=>['admin.voucher-categories.update'],'method' => 'post','id' => 'general-form', 'enctype' => 'multipart/form-data']) }}
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
                                                <input id="start_date" name="start_date" type="text" class="date-time-picker form-control" value="{{ $voucher->start_date }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="finish_date">Finish Date *</label>
                                                <input id="finish_date" type="text" class="date-time-picker form-control"
                                                       name="finish_date" value="{{ $voucher->finish_date }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                    <a href="{{ route('admin.voucher.index') }}" class="btn btn-danger">Exit</a>
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