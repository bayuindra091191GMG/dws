@extends('layouts.admin-demo')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('partials.admin._messages')
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1>DAFTAR TRANSAKSI</h1>
                    </div>
                </div>
                {{--<div class="row mb-3">--}}
                {{--<div class="col-12 text-right">--}}
                {{--<a href="{{ route('admin.transactions.on_demand.dws.create') }}" class="btn btn-success">--}}
                {{--<i class="fas fa-plus text-white"></i>--}}
                {{--<br/>--}}
                {{--<span>KATEGORI DWS</span>--}}
                {{--</a>--}}
                {{--<a href="{{ route('admin.transactions.on_demand.masaro.create') }}" class="btn btn-success">--}}
                {{--<i class="fas fa-plus text-white"></i>--}}
                {{--<br/>--}}
                {{--<span>KATEGORI MASARO</span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="row">
                    <div class="col-12">
                        <h2 class="card-title m-b-0"></h2>
                        <table id="datatable-ondemand" class="table table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="text-center">Jenis Kategori</th>
                                <th class="text-center">Jenis Barang</th>
                                <th class="text-center">Total Berat (kilogram)</th>
                                <th class="text-center">Point</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Sampah Bakar</td>
                                    <td>Daun Kering</td>
                                    <td>5</td>
                                    <td>50</td>
                                </tr>
                                <tr>
                                    <td>Sampah Busuk</td>
                                    <td>Sampah Sayuran</td>
                                    <td>3</td>
                                    <td>30</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

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
                console.log("Notification permission granted.");

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
            })
            .catch(function (err) {
                console.log("Unable to get permission to notify.", err);
            });

        messaging.onMessage(function(payload) {
            $("#datatable-ondemand tbody").prepend(
                "<tr>" +
                "<td>" + payload.data.category +"</td>" +
                "<td>" + payload.data.item + "</td>" +
                "<td>" + payload.data.weight + "</td>" +
                "<td>" + payload.data.point + "</td>" +
                "</tr>"
            );
            // console.log("Message received. ", payload);
        });
    </script>
@endsection