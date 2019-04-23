@extends('layouts.admin')

@section('content')
    <!-- ============================================================== -->
    <!-- Recent comment and chats -->
    <!-- ============================================================== -->
    {{--<div class="row">--}}
        {{--<div class="col-lg-6">--}}
            {{--<h3>Pendapatan</h3>--}}
        {{--</div>--}}
        {{--<div class="col-lg-6 text-right">--}}
            {{--<select class="select2 form-control custom-select" style="width: 20%; height:36px;">--}}
                {{--<option>Select</option>--}}
                {{--<option>2018</option>--}}
            {{--</select>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="row">

    @foreach($dashboardDatas as $dashboardData)
        <!-- column -->
        <div class="col-lg-4">
            <!-- card new -->
            <div class="card">
                <div class="card-body">
                    <span class="badge badge-success font-bold">{{ $dashboardData->get('month') }}</span>
                </div>

                    <ul class="list-style-none">
                        <li class="d-flex no-block card-body">
                            <div class="col-lg-12">
                                <span class="text-muted">Total Nilai Sampah</span><br>
                                <span class="text-muted size-30">Rp </span><span class="text-muted font-bold size-33">{{ number_format($dashboardData->get('totalPrice'), 2, ",", ".") }}</span>
                            </div>
                        </li>
                        <li class="d-flex no-block card-body border-top">
                            <div class="col-12">
                                <span class="text-muted">Total Berat</span>
                                <br>
                                @if($dashboardData->get('totalWeight') > 0)
                                    <span class="text-muted font-bold size-33">{{ number_format($dashboardData->get('totalWeight') / 1000, 2, ",", ".") }} </span><span class="text-muted size-30">Kg</span>
                                @else
                                    <span class="text-muted font-bold size-33">0 </span><span class="text-muted size-30">Kg</span>
                                @endif
                            </div>
                        </li>
                        <li class="d-flex no-block card-body border-top">
                            <div class="row w-100">
                                <div class="col-6">
                                    <span>Jenis Sampah</span>
                                </div>
                                <div class="col-3 text-right">
                                    <span>Berat (Gram)</span>
                                </div>
                                <div class="col-3 text-right">
                                    <span>Harga (Rp)</span>
                                </div>
                            </div>
                        </li>
                    </ul>

{{--                @if($trxDetailRutin->count() > 0)--}}
{{--                    <ul class="list-style-none">--}}
{{--                        <li class="d-flex no-block card-body">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <span class="text-muted">Total Nilai Sampah</span><br>--}}
{{--                                <span class="text-muted size-30">Rp </span><span class="text-muted font-bold size-33">{{ $totalRutinWasteWeight }}</span>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="col-12">--}}
{{--                                <span class="text-muted">Total Berat</span>--}}
{{--                                <br>--}}
{{--                                <span class="text-muted font-bold size-33">{{ $totalRutinWastePrice }} </span><span class="text-muted size-30">Gram</span>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-6">--}}
{{--                                    <span>Jenis Sampah</span>--}}
{{--                                </div>--}}
{{--                                <div class="col-3 text-right">--}}
{{--                                    <span>Berat (Gram)</span>--}}
{{--                                </div>--}}
{{--                                <div class="col-3 text-right">--}}
{{--                                    <span>Harga (Rp)</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        @foreach($trxDetailRutin as $trxDetail)--}}
{{--                            <li class="d-flex no-block card-body no-padding">--}}
{{--                                <div class="row w-100">--}}
{{--                                    <div class="col-6">--}}
{{--                                        @if($adminBankCatId === 1)--}}
{{--                                            <span class="text-muted">{{ $trxDetail->dws_waste_category_data->name }}</span>--}}
{{--                                        @else--}}
{{--                                            <span class="text-muted">{{ $trxDetail->masaro_waste_category_data->name }}</span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="text-right">--}}
{{--                                            <span class="text-muted font-bold">{{ $trxDetail->weight_string }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="text-right">--}}
{{--                                            <span class="text-muted font-bold">{{ $trxDetail->price_string }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-12 text-center">--}}
{{--                                    <a href="{{ route('admin.transactions.penjemputan_rutin.index') }}" class="btn btn-success btn-lg text-white">Lihat Semua</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                @else--}}
{{--                    <ul class="list-style-none">--}}
{{--                        <li class="d-flex no-block card-body">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <span class="text-muted size-30 font-bold">Tidak Ada Transaksi</span><br>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                @endif--}}

            </div>
        </div>

    @endforeach
        <!-- column -->
        <!-- column -->
{{--        <div class="col-lg-4">--}}
{{--            <!-- card new -->--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <span class="badge badge-success font-bold">TRANSAKSI ANTAR SENDIRI</span>--}}
{{--                </div>--}}

{{--                @if($trxDetailAntarSendiri->count() > 0)--}}
{{--                    <ul class="list-style-none">--}}
{{--                        <li class="d-flex no-block card-body">--}}
{{--                            <div class="col-lg-12 text-center">--}}
{{--                                <span class="text-muted">Total Nilai Sampah</span><br>--}}
{{--                                <span class="text-muted size-30">Rp </span><span class="text-muted font-bold size-33">{{ $totalAntarSendiriWastePrice }}</span>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="col-12 text-center">--}}
{{--                                <span class="text-muted">Total Berat</span>--}}
{{--                                <br>--}}
{{--                                <span class="text-muted font-bold size-33">{{ $totalAntarSendiriWasteWeight }} </span><span class="text-muted size-30">Gram</span>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-6">--}}
{{--                                    <span>Jenis Sampah</span>--}}
{{--                                </div>--}}
{{--                                <div class="col-3 text-right">--}}
{{--                                    <span>Berat (Gram)</span>--}}
{{--                                </div>--}}
{{--                                <div class="col-3 text-right">--}}
{{--                                    <span>Harga (Rp)</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        @foreach($trxDetailAntarSendiri as $trxDetail)--}}
{{--                            <li class="d-flex no-block card-body no-padding">--}}
{{--                                <div class="row w-100">--}}
{{--                                    <div class="col-6">--}}
{{--                                        @if($adminBankCatId === 1)--}}
{{--                                            <span class="text-muted">{{ $trxDetail->dws_waste_category_data->name }}</span>--}}
{{--                                        @else--}}
{{--                                            <span class="text-muted">{{ $trxDetail->masaro_waste_category_data->name }}</span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="text-right">--}}
{{--                                            <span class="text-muted font-bold">{{ $trxDetail->weight_string }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="text-right">--}}
{{--                                            <span class="text-muted font-bold">{{ $trxDetail->price_string }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-12 text-center">--}}
{{--                                    <a href="{{ route('admin.transactions.antar_sendiri.index') }}" class="btn btn-success btn-lg text-white">Lihat Semua</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                @else--}}
{{--                    <ul class="list-style-none">--}}
{{--                        <li class="d-flex no-block card-body">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <span class="text-muted size-30 font-bold">Tidak Ada Transaksi</span><br>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                @endif--}}

{{--            </div>--}}
{{--        </div>--}}
{{--        <!-- column -->--}}
{{--        <!-- column -->--}}
{{--        <div class="col-lg-4">--}}
{{--            <!-- card new -->--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <span class="badge badge-success font-bold">TRANSAKSI INSTAN</span>--}}
{{--                </div>--}}
{{--                @if($trxDetailInstant->count() > 0)--}}
{{--                    <ul class="list-style-none">--}}
{{--                        <li class="d-flex no-block card-body">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <span class="text-muted">Total Nilai Sampah</span><br>--}}
{{--                                <span class="text-muted size-30">Rp </span><span class="text-muted font-bold size-33">{{ $totalInstantWastePrice }}</span>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="col-12">--}}
{{--                                <span class="text-muted">Total Berat</span>--}}
{{--                                <br>--}}
{{--                                <span class="text-muted font-bold size-33">{{ $totalInstantWasteWeight }} </span><span class="text-muted size-30">Gram</span>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-6">--}}
{{--                                    <span>Jenis Sampah</span>--}}
{{--                                </div>--}}
{{--                                <div class="col-3 text-right">--}}
{{--                                    <span>Berat (Gram)</span>--}}
{{--                                </div>--}}
{{--                                <div class="col-3 text-right">--}}
{{--                                    <span>Harga (Rp)</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        @foreach($trxDetailInstant as $trxDetail)--}}
{{--                            <li class="d-flex no-block card-body no-padding">--}}
{{--                                <div class="row w-100">--}}
{{--                                    <div class="col-6">--}}
{{--                                        @if($adminBankCatId === 1)--}}
{{--                                            <span class="text-muted">{{ $trxDetail->dws_waste_category_data->name }}</span>--}}
{{--                                        @else--}}
{{--                                            <span class="text-muted">{{ $trxDetail->masaro_waste_category_data->name }}</span>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="text-right">--}}
{{--                                            <span class="text-muted font-bold">{{ $trxDetail->weight_string }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-3">--}}
{{--                                        <div class="text-right">--}}
{{--                                            <span class="text-muted font-bold">{{ $trxDetail->price_string }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                        <li class="d-flex no-block card-body border-top">--}}
{{--                            <div class="row w-100">--}}
{{--                                <div class="col-12 text-center">--}}
{{--                                    <a href="{{ route('admin.transactions.on_demand.index') }}" class="btn btn-success btn-lg text-white">Lihat Semua</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                @else--}}
{{--                    <ul class="list-style-none">--}}
{{--                        <li class="d-flex no-block card-body">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <span class="text-muted size-30 font-bold">Tidak Ada Transaksi</span><br>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                @endif--}}

{{--            </div>--}}
{{--        </div>--}}
        <!-- column -->
    </div>
    <!-- Sales chart -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-6">
            <h3>Volume Sampah</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- column -->
                        <div class="col-lg-12">
                            <div class="flot-chart">
                                <div class="flot-chart-content" id="flot-line-chart"></div>
                            </div>
                        </div>
                        <!-- column -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Sales chart -->
    <!-- ============================================================== -->
@endsection
@section('styles')
    <style>
        .size-30{
            font-size: 30px;
            color: black;
        }
        .size-33{
            font-size: 33px;
            color: black !important;
        }
        .width-25{
            width: 25%;
        }
        .no-padding{
            padding-left: 1.25em;
            padding-top:5px;
            padding-bottom:5px;
        }
        .badge-success{
            border-radius: 20px;
            padding: 2% 5% 2% 5%;
        }
    </style>
@endsection
@section('scripts')
    <!--This page JavaScript -->
    <!-- <script src="../../dist/js/pages/dashboards/dashboard1.js"></script> -->
    <!-- Charts js Files -->
    <script src="{{ asset('backend/assets/libs/flot/excanvas.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/flot/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('backend/dist/js/pages/chart/chart-page-init.js') }}"></script>
@endsection