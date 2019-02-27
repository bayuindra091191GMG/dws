@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="row">
                        <div class="col-12">
                            <h2>Detail Waste Collector {{ $collector->first_name }} {{ $collector->last_name }}</h2>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12 text-right">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body b-b">

                                    @if(\Illuminate\Support\Facades\Session::has('message'))
                                        <div class="col-md-12">
                                            <div class="form-group form-float form-group-lg">
                                                <div class="form-line">
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <strong>{{ \Illuminate\Support\Facades\Session::get('message') }}</strong>
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if(\Illuminate\Support\Facades\Session::has('error'))
                                        <div class="col-md-12">
                                            <div class="form-group form-float form-group-lg">
                                                <div class="form-line">
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        <strong>{{ \Illuminate\Support\Facades\Session::get('error') }}</strong>
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="email">Email</label>
                                                <input id="email" type="text" class="form-control"
                                                       name="email" value="{{ $collector->email }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="status">Status</label>
                                                <input id="status" type="text" class="form-control"
                                                       name="status" value="{{ $collector->status->description }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="name">Nama</label>
                                                <input id="name" type="text" class="form-control"
                                                       name="name" value="{{ $name }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="identity_number">No. Identitas</label>
                                                <input id="identity_number" type="text" class="form-control"
                                                       name="identity_number" value="{{ $collector->identity_number }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="phone">No. Ponsel</label>
                                                <input id="phone" type="text" class="form-control"
                                                       name="phone" value="{{ $collector->phone }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="address">Alamat</label>
                                                <input id="address" type="text" class="form-control"
                                                       name="address" value="{{ $collector->address }} gram" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="picture">Foto</label>
                                                <img src="{{ asset('storage/admin/wastecollector/'. $collector->img_path) }}" style="height: 200px; width: auto;"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <a href="{{ route('admin.wastecollectors.index') }}" class="btn btn-danger">Exit</a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--<div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="modalConfirm" aria-hidden="true">--}}
        {{--<div class="modal-dialog" role="document">--}}
            {{--<div class="modal-content">--}}
                {{--{{ Form::open(['route'=>['admin.transactions.on_demand.confirm'],'method' => 'post','id' => 'general-form', 'novalidate']) }}--}}

                {{--<div class="modal-header">--}}
                    {{--<h5 class="modal-title">KONFIRMASI TRANSAKSI</h5>--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                        {{--<span aria-hidden="true">&times;</span>--}}
                    {{--</button>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--Apakah anda yakin konfirmasi transaksi ini?--}}
                    {{--<input type="hidden" name="confirmed_header_id" value="{{ $header->id }}"/>--}}
                {{--</div>--}}
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>--}}
                    {{--<input type="submit" class="btn btn-primary" value="KONFIRMASI">--}}
                {{--</div>--}}
                {{--{{ Form::close() }}--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

@endsection

@section('styles')

@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // $('#waste_collector_id').select2();
            //
            // $(document).on('click', '#btn-confirm', function(){
            //     $('#modal-confirm').modal('show');
            // })
        });
    </script>
@endsection