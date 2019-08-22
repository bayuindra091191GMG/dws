@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>LAPORAN TRANSAKSI</h3>
                    </div>
                </div>

                {{ Form::open(['route'=>['admin.transaction.report.submit'],'method' => 'post','id' => 'general-form']) }}

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">
                                <div class="body">
                                    @if(count($errors))
                                        <div class="col-md-12">
                                            <div class="form-group form-float form-group-lg">
                                                <div class="form-line">
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <ul>
                                                            @foreach($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="date_start">Dari Tanggal *</label>
                                                <input id="date_start" type="text" class="form-control"
                                                       name="date_start" value="{{ $dateStart }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="date_end">Sampai Tanggal *</label>
                                                <input id="date_end" type="text" class="form-control"
                                                       name="date_end" value="{{ $dateEnd }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="transaction_type">Jenis Transaksi</label>
                                            <select id="transaction_type" name="transaction_type" class="form-control">
                                                <option value="0">Semua</option>
                                                <option value="1">Transaksi Rutin</option>
                                                <option value="2">Transaksi Antar Sendiri</option>
                                                <option value="3">Transaksi Penjemputan Sekarang</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="waste_category">Kategori Sampah</label>
                                            <select id="waste_category" name="waste_category" class="form-control">
                                                <option value="0">Semua</option>
                                                <option value="1">Kategori DWS</option>
                                                <option value="2">Kategori Masaro</option>
                                            </select>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                    <input type="submit" class="btn btn-success" value="UNDUH LAPORAN">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        jQuery('#date_start').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd M yyyy"
        });

        jQuery('#date_end').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd M yyyy"
        });
    </script>
@endsection
