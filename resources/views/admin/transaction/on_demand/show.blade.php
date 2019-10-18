@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8 col-12">
                        <a href="{{ route('admin.transactions.on_demand.index') }}" class="btn btn-outline-primary float-left">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h3 class="mb-0 float-left ml-3">DETAIL TRANSAKSI PENJEMPUTAN SEKARANG</h3>
                    </div>
                    <div class="col-md-4 col-12 text-right">

                        @if($header->status_id === 8)
                            <button type="button" class="btn btn-primary" id="btn-confirm">KONFIRMASI</button>

                            @if($header->waste_category_id === 1)
                                <a href="{{ route('admin.transactions.on_demand.dws.edit', ['id' => $header->id]) }}" class="btn btn-primary">UBAH</a>
                            @else
                                <a href="{{ route('admin.transactions.masaro.on_demand.edit', ['id' => $header->id]) }}" class="btn btn-primary">UBAH</a>
                            @endif
                        @endif

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
                                            <label class="form-label" for="name">No Transaksi</label>
                                            <input id="trxNo" type="text" class="form-control"
                                                   name="trxNo" value="{{ $header->transaction_no }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="date">Tanggal Transaksi</label>
                                            <input id="date" type="text" class="form-control"
                                                   name="date" value="{{ $date }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="name">Nama Sumber Sampah</label>
                                            <input id="name" type="text" class="form-control"
                                                   name="name" value="{{ $name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                @if(empty($header->waste_collector_id))

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="waste_collector_id">Petugas Kebersihan</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        {{ Form::open(['route'=>['admin.transactions.on_demand.assign', $header->id],'method' => 'post', 'id' => 'general-form']) }}

                                        <div class="form-group row">
                                            <div class="col-md-8">
                                                <select id="waste_collector_id" name="waste_collector_id" class="select2 form-control custom-select" style="width: 100%; height: 36px !important;">
                                                    <option value="-1"> - Pilih Petugas Kebersihan - </option>
                                                    @foreach($wasteCollectors as $wasteCollector)
                                                        <option value="{{ $wasteCollector->id }}">{{ $wasteCollector->first_name. ' '. $wasteCollector->last_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="submit" class="btn btn-success" value="ASSIGN"/>
                                            </div>
                                        </div>

                                        {{ Form::close() }}

                                    </div>
                                @else
                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="waste_collector">Petugas Kebersihan</label>
                                                <input id="waste_collector" type="text" class="form-control"
                                                       name="waste_collector" value="{{ $header->waste_collector->first_name. ' '. $header->waste_collector->last_name }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="waste_bank">Pengolahan Sampah</label>
                                            <input id="waste_bank" type="text" class="form-control"
                                                   name="waste_bank" value="{{ $header->waste_bank->name ?? '-' }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="transaction_type">Jenis Transaksi</label>
                                            <input id="transaction_type" type="text" class="form-control"
                                                   name="transaction_type" value="{{ $header->transaction_type->description }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="waste_category">Kategori</label>
                                            <input id="waste_category" type="text" class="form-control"
                                                   name="waste_category" value="{{ $header->waste_category->name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="total_weight">Berat Total</label>
                                            <input id="total_weight" type="text" class="form-control"
                                                   name="total_weight" value="{{ $header->total_weight_kg_string }} kg" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="total_price">Harga Total</label>
                                            <input id="total_price" type="text" class="form-control"
                                                   name="total_price" value="Rp {{ $header->total_price_string }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group form-float form-group-lg">
                                        <div class="form-line">
                                            <label class="form-label" for="meta_description">Status</label>
                                            <input id="status" type="text" class="form-control"
                                                   name="status" value="{{ $header->status->description }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered dt-responsive nowrap">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Kode</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">Berat (kg)</th>
                                                <th class="text-center">Harga (Rp)</th>
                                                <th class="text-center">Catatan</th>
                                                <th class="text-center">Foto</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($header->waste_category_id == 1)
                                                @foreach($header->transaction_details as $detail)
                                                    <tr>
                                                        <td>{{ $detail->dws_waste_category_data->code ?? '' }}</td>
                                                        <td>{{ $detail->dws_waste_category_data->name }}</td>
                                                        <td class="text-right">{{ $detail->weight_kg_string }}</td>
                                                        <td class="text-right">{{ $detail->price_string }}</td>
                                                        <td>{{ $detail->note ?? "" }}</td>
                                                        <td><a class="fancybox-viewer" href="{{ asset('transactions/ondemand/'. $detail->getOriginal('image_path')) }}"><img src="{{ asset('storage/transactions/ondemand/'. $detail->getOriginal('image_path')) }}" alt=""/></a></td>
                                                    </tr>
                                                @endforeach
                                            @elseif($header->waste_category_id == 2)
                                                @foreach($header->transaction_details as $detail)
                                                    <tr>
                                                    <tr>
                                                        <td>{{ $detail->masaro_waste_category_data->code?? '' }}</td>
                                                        <td>{{ $detail->masaro_waste_category_data->name }}</td>
                                                        <td class="text-right">{{ $detail->weight_kg_string }}</td>
                                                        <td class="text-right">{{ $detail->price_string }}</td>
                                                        <td>{{ $detail->note ?? "" }}</td>
                                                        <td class="text-center"><a class="fancybox-viewer" href="{{ asset('storage/transactions/ondemand/'. $detail->getOriginal('image_path')) }}"><img src="{{ asset('storage/transactions/ondemand/'. $detail->getOriginal('image_path')) }}" alt=""/></a></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-confirm" tabindex="-1" role="dialog" aria-labelledby="modalConfirm" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{ Form::open(['route'=>['admin.transactions.on_demand.confirm'],'method' => 'post','id' => 'general-form', 'novalidate']) }}

                <div class="modal-header">
                    <h5 class="modal-title">KONFIRMASI TRANSAKSI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin konfirmasi transaksi ini?
                    <input type="hidden" name="confirmed_header_id" value="{{ $header->id }}"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">BATAL</button>
                    <input type="submit" class="btn btn-primary" value="KONFIRMASI">
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" type="text/css" media="screen" />
    <style>
        .fancybox-viewer img{
            width: 100px;
            height: auto;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>
    <script>
        $(document).ready(function() {
            $('#waste_collector_id').select2();

            $(document).on('click', '#btn-confirm', function(){
                $('#modal-confirm').modal('show');
            });

            $("a.fancybox-viewer").fancybox();
        });
    </script>
@endsection
