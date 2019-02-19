@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('partials.admin._messages')
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1>DAFTAR TRANSAKSI ON DEMAND</h1>
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
                        <table id="datatable-ondemand" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">No Transaksi</th>
                                <th class="text-center">Nama User</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Total Berat (gram)</th>
                                <th class="text-center">Total Harga (Rp)</th>
                                <th class="text-center">Waste Bank</th>
                                <th class="text-center">Waste Collector</th>
                                <th class="text-center">Status</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaction->date)->format('j-F-Y H:i:s')}}</td>
                                    <td>{{ $trasaction->transaction_no}}</td>
                                    <td>{{ $transaction->user->first_name}} {{ $transaction->user->last_name}}</td>
                                    <td>{{ $transaction->waste_category->name}}</td>
                                    <td>{{ $transaction->total_weight}}</td>
                                    <td>{{ $transaction->total_price}}</td>
                                    <td>
                                        @php($wasteBank = "-")
                                        @if(!empty($transaction->waste_bank_id))
                                            @php($wasteBank = $transaction->waste_bank->name)
                                        @endif
                                        {{ $wasteBank}}
                                    </td>
                                    <td>
                                        @php($wasteCollector = "-")
                                        @if(!empty($transaction->waste_collector_id))
                                            @php($wasteCollector = $transaction->waste_collector->first_name. ' '. $transaction->waste_collector->last_name)
                                        @endif
                                        {{ $wasteCollector}}
                                    </td>
                                    <td>{{ $transaction->status->description}}</td>
                                    <td>
                                        <a class='btn btn-xs btn-info' href='{{route('admin.transactions.on_demand.show', ['id' => $transaction->id])}}' data-toggle='tooltip' data-placement='top'><i class='fas fa-info'></i></a>
                                    </td>
                                </tr>
                                @php ($idx++)
                            @endforeach
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
            var typeId = payload.data.type_id;
            if(typeId === '2'){
                $("#datatable-ondemand tbody").prepend(
                    "<tr>" +
                    "<td>" + payload.data.transaction_date +"</td>" +
                    "<td>" + payload.data.transaction_no + "</td>" +
                    "<td>" + payload.data.name + "</td>" +
                    "<td>" + payload.data.waste_category_name + "</td>" +
                    "<td>" + payload.data.total_weight + "</td>" +
                    "<td>" + payload.data.total_price + "</td>" +
                    "<td>" + payload.data.waste_bank + "</td>" +
                    "<td>" + payload.data.waste_collector + "</td>" +
                    "<td>" + payload.data.status + "</td>" +
                    "<td>" +
                    "<a class='btn btn-xs btn-info' href='/admin/transactions/on_demand/show/"+ payload.data.transaction_id + "' data-toggle='tooltip' data-placement='top'><i class='fas fa-info'></i></a>" +
                    "</td>" +
                    "</tr>"
                );
            }
            // console.log("Message received. ", payload);
        });
    </script>
    <script>

        $('#datatable-ondemand').DataTable( {
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
            }
        } );
        $(document).on('click', '.delete-modal', function(){
            $('#deleteModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#deleted-id').val($(this).data('id'));
        });
    </script>
    @include('partials._deletejs', ['routeUrl' => 'admin.users.destroy', 'redirectUrl' => 'admin.users.index'])
@endsection