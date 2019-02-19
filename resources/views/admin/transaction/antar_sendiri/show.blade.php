@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-body">
            <h2 class="card-title m-b-0">Detail Transaksi</h2>
            <div class="container-fluid relative animatedParent animateOnce">
                <div class="row mb-2">
                    <div class="col-10"></div>
                    <div class="col-2 text-right">

                        @if($header->waste_category_id == "1")
                            <a href="{{ route('admin.transactions.antar_sendiri.dws.edit', ['id' => $header->id]) }}" class="btn btn-primary">UBAH</a>
                        @else
                            <a href="{{ route('admin.transactions.antar_sendiri.masaro.edit', ['id' => $header->id]) }}" class="btn btn-primary">UBAH</a>
                        @endif

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">

                                <div class="col-md-12">
                                    <div id="user-action-confirm" class="form-group form-float form-group-lg" style="display: none;">
                                        <div class="form-line">
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>Transaksi Dikonfirmasi oleh User</strong>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div id="user-action-cancel" class="form-group form-float form-group-lg" style="display: none;">
                                        <div class="form-line">
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <strong>Transaksi Dibatalkan oleh User</strong>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                            <a href="{{ route('admin.transactions.antar_sendiri.index') }}" class="btn btn-danger">Exit</a>
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
@section('scripts')
    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js"></script>

    <!-- Add additional services that you want to use -->
    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js"></script>
    {{--<script src="{{ asset('js/fcm-notif.js') }}"></script>--}}
    <script>
        // MsgElem = document.getElementById("msg")
        // TokenElem = document.getElementById("token")
        // NotisElem = document.getElementById("notis")
        // ErrElem = document.getElementById("err")
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
        var config = {
            apiKey: "{{env('FCM_API_KEY')}}",
            authDomain: "{{env('FCM_DOMAIN')}}.firebaseapp.com",
            databaseURL: "https://{{env('FCM_DOMAIN')}}.firebaseio.com",
            projectId: "{{env('FCM_DOMAIN')}}",
            storageBucket: "{{env('FCM_DOMAIN')}}.appspot.com",
            messagingSenderId: "{{env('FCM_MESSANGING_SENDER')}}"
        };
        firebase.initializeApp(config);

        const messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                // MsgElem.innerHTML = "Notification permission granted."
                // console.log("Notification permission granted.");

                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                //save token to DB
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.save.token') }}',
                    datatype : "application/json",
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'token': token
                    }, // no need to stringify
                    success: function (result) {
                        console.log("Success save token.");
                    }
                });
                // TokenElem.innerHTML = "token is : " + token
            })
            .catch(function (err) {
                // ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                // console.log("Unable to get permission to notify.", err);
            });

        messaging.onMessage(function(payload) {
            var transactionNo = payload.data.transaction_no;
            var transNo = $('#trxNo').val();

            if(transactionNo === transNo){
                var isConfirm = payload.data.is_confirm;
                var name = payload.data.name;

                if(isConfirm === '1'){
                    $("#user-action-confirm").show();
                    $("#user-action-cancel").hide();
                }
                else{
                    $("#user-action-cancel").show();
                    $("#user-action-confirm").hide();
                }
                $('#name').val(name);
            }
            // console.log("Message received. ", payload);
            // NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload)
        });
    </script>
@endsection