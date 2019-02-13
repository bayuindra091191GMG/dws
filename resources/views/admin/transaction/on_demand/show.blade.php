@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Detail Transaksi</h2>
                <div class="container-fluid relative animatedParent animateOnce">
                    <div class="row mb-2">
                        <div class="col-12 text-right">

                            {{--@if($header->waste_category_id == "1")--}}
                                {{--<a href="{{ route('admin.transactions.antar_sendiri.dws.edit', ['id' => $header->id]) }}" class="btn btn-primary">UBAH</a>--}}
                            {{--@else--}}
                                {{--<a href="{{ route('admin.transactions.antar_sendiri.masaro.edit', ['id' => $header->id]) }}" class="btn btn-primary">UBAH</a>--}}
                            {{--@endif--}}

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
                                                <label class="form-label" for="name">Nama User</label>
                                                <input id="name" type="text" class="form-control"
                                                       name="name" value="{{ $name }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    @if(empty($header->waste_collector_id))

                                        <div class="col-md-12">
                                            <div class="form-group form-float form-group-lg">
                                                <div class="form-line">
                                                    <label class="form-label" for="waste_collector_id">Waste Collector</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">

                                            {{ Form::open(['route'=>['admin.transactions.on_demand.assign', $header->id],'method' => 'post', 'id' => 'general-form']) }}

                                            <div class="form-group row">
                                                <div class="col-md-8">
                                                    <select id="waste_collector_id" name="waste_collector_id" class="select2 form-control custom-select" style="width: 100%; height: 36px !important;">
                                                        <option value="-1"> - Pilih Waste Collector - </option>
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
                                                    <label class="form-label" for="waste_collector">Waste Collector</label>
                                                    <input id="waste_collector" type="text" class="form-control"
                                                           name="waste_collector" value="{{ $header->waste_collector->first_name. ' '. $header->waste_collector->last_name }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    @endif


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
                                                       name="total_weight" value="{{ $header->total_weight_string }} gram" readonly>
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
                                        <table class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Kode</th>
                                                <th class="text-center">Nama</th>
                                                <th class="text-center">Berat (gram)</th>
                                                <th class="text-center">Harga (Rp)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if($header->waste_category_id == 1)
                                                @foreach($header->transaction_details as $detail)
                                                    <tr>
                                                        <td>{{ $detail->dws_waste_category_data->code }}</td>
                                                        <td>{{ $detail->dws_waste_category_data->name }}</td>
                                                        <td class="text-right">{{ $detail->weight_string }}</td>
                                                        <td class="text-right">{{ $detail->price_string }}</td>
                                                    </tr>
                                                @endforeach
                                            @elseif($header->waste_category_id == 2)
                                                @foreach($header->transaction_details as $detail)
                                                    <tr>
                                                    <tr>
                                                        <td>{{ $detail->masaro_waste_category_data->code }}</td>
                                                        <td>{{ $detail->masaro_waste_category_data->name }}</td>
                                                        <td class="text-right">{{ $detail->weight_string }}</td>
                                                        <td class="text-right">{{ $detail->price_string }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <a href="{{ route('admin.transactions.on_demand.index') }}" class="btn btn-danger">Exit</a>
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

@section('styles')

@endsection

@section('scripts')
    <script src="{{ asset('backend/assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#waste_collector_id').select2();
        });
    </script>
@endsection