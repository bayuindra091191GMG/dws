@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-body">
            <h2 class="card-title m-b-0">Detail Transaksi</h2>
            <div class="container-fluid relative animatedParent animateOnce">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">
                                <div class="tab-content pb-3" id="v-pills-tabContent">
                                    <div class="tab-pane animated fadeInUpShort show active" id="v-pills-1">
                                        <div class="body">
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
                                                        <label class="form-label" for="slug">Nama User</label>
                                                        <input id="name" type="text" class="form-control"
                                                               name="name" value="{{ $header->user->first_name . ' ' . $header->user->last_name }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group form-float form-group-lg">
                                                    <div class="form-line">
                                                        <label class="form-label" for="meta_title">Tipe Transaksi</label>
                                                        <input id="transaction_type" type="text" class="form-control"
                                                               name="transaction_type" value="{{ $header->transaction_type->description }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group form-float form-group-lg">
                                                    <div class="form-line">
                                                        <label class="form-label" for="meta_description">Kategori</label>
                                                        <input id="waste_category" type="text" class="form-control"
                                                               name="waste_category" value="{{ $header->waste_category->name }}" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group form-float form-group-lg">
                                                    <div class="form-line">
                                                        <label class="form-label" for="meta_description">Berat Total</label>
                                                        <input id="total_weight" type="text" class="form-control"
                                                               name="total_weight" value="{{ $header->total_weight }}kg" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group form-float form-group-lg">
                                                    <div class="form-line">
                                                        <label class="form-label" for="meta_description">Harga Total</label>
                                                        <input id="total_price" type="text" class="form-control"
                                                               name="total_price" value="Rp{{ $header->total_price }}" readonly>
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

                                            <table class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <td>Kode Kategori</td>
                                                    <td>Berat</td>
                                                    <td>Harga</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($header->waste_category_id == 1)
                                                    @foreach($header->transaction_details as $detail)
                                                        <tr>
                                                            <td>{{ $detail->dws_waste_category_data->code }}</td>
                                                            <td>{{ $detail->weight }}kg</td>
                                                            <td>Rp{{ $detail->price }}</td>
                                                        </tr>
                                                    @endforeach
                                                @elseif($header->waste_category_id == 2)
                                                    @foreach($header->transaction_details as $detail)
                                                        <tr>
                                                        <tr>
                                                            <td>{{ $detail->masaro_waste_category_data->code }}</td>
                                                            <td>{{ $detail->weight }}kg</td>
                                                            <td>Rp{{ $detail->price }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>

                                        </div>
                                        <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                            <a href="{{ route('admin.transactions.index') }}" class="btn btn-danger">Exit</a>
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

@endsection