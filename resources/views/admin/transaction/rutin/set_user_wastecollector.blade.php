@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-body">
            <h2 class="card-title m-b-0">Simpan Wastecollector kepada User</h2>
            <div class="container-fluid relative animatedParent animateOnce">
                <div class="row mb-2">
                    <div class="col-10"></div>
                    <div class="col-2 text-right">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">

                                {{ Form::open(['route'=>['admin.user.penjemputan_rutin.update'],'method' => 'post','id' => 'general-form']) }}
                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="name">Nama User</label>
                                            <input type="text" class="form-control"
                                                   value="{{ $user->first_name }} {{ $user->last_name    }}" readonly>
                                            <input id="user_id" name="user_id" type="hidden" value="{{ $user->id }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="phone">Phone</label>
                                            <input id="phone" type="text" value="{{ $user->phone }}"
                                                   class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="address">Alamat</label>
                                            <textarea id="address" class="form-control" readonly>{{ $address->description }}, {{$address->City->name}}, {{$address->Province->name}}, {{$address->postal_code}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="address">Cari Wastecollector *</label>
                                            <select id="wastecollector" name="wastecollector" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <a href="{{ route('admin.user.penjemputan_rutin.index') }}" class="btn btn-danger">Exit</a>
                                            <input type="submit" class="btn btn-success" value="Save">
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        $('#wastecollector').select2({
            placeholder: {
                id: '-1',
                text: 'Pilih Wastecollector...'
            },
            width: '100%',
            minimumInputLength: 0,
            ajax: {
                url: '{{ route('select.wastecollectors') }}',
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