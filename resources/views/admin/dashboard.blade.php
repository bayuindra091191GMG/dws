@extends('layouts.admin')

@section('content')
    <!-- ============================================================== -->
    <!-- Recent comment and chats -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-lg-6">
            <h3>Pendapatan</h3>
            <h4>Rangkuman</h4>
        </div>
        <div class="col-lg-6 text-right">
            <select class="select2 form-control custom-select" style="width: 20%; height:36px;">
                <option>Select</option>
                <option>2018</option>
            </select>
        </div>
    </div>
    <div class="row">
        <!-- column -->
        <div class="col-lg-4">
            <!-- card new -->
            <div class="card">
                <div class="card-body">
                    <span class="badge badge-success">Januari</span>
                    <i class="m-r-10 mdi mdi-folder-download size-33" style="padding-left:50%;"></i>
                </div>
                <ul class="list-style-none">
                    <li class="d-flex no-block card-body">
                        <div class="col-lg-12">
                            <span class="text-muted">Total Nilai Sampah</span><br>
                            <span class="text-muted size-30">Rp </span><span class="text-muted font-bold size-33">159.030.000</span>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body border-top">
                        <div class="col-12">
                            <span class="text-muted">Total Berat</span>
                            <br>
                            <span class="text-muted font-bold size-33">2.051 </span><span class="text-muted size-30">kg</span>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body border-top">
                        <div>
                            <span class="text-muted">Jenis Sampah</span>
                        </div>
                    </li>
                    @for($i = 0; $i<4; $i++)
                        <li class="d-flex no-block card-body no-padding">
                            <div class="width-25">
                                <span class="text-muted">Plastik</span>
                            </div>
                            <div>
                                <div class="tetx-right">
                                    <span class="text-muted font-bold">800 Kg</span>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <div class="tetx-right">
                                    <span class="text-muted font-bold">Rp40.588.000</span>
                                </div>
                            </div>
                        </li>
                    @endfor
                    <li class="d-flex no-block card-body no-padding">
                        <div class="width-25">
                            <span class="text-muted">Elektronik</span>
                        </div>
                        <div>
                            <div class="tetx-right">
                                <span class="text-muted font-bold">800 Kg</span>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <div class="tetx-right">
                                <span class="text-muted font-bold">Rp40.588.000</span>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body no-padding">
                        <div class="width-25">
                            <span class="text-muted">Lainnya</span>
                        </div>
                        <div>
                            <div class="tetx-right">
                                <span class="text-muted font-bold">123456 Kg</span>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <div class="tetx-right">
                                <span class="text-muted font-bold">Rp40.588.000</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- column -->
        <!-- column -->
        <div class="col-lg-4">
            <!-- card new -->
            <div class="card">
                <div class="card-body">
                    <span class="badge badge-success">Mei</span>
                    <i class="m-r-10 mdi mdi-folder-download size-33" style="padding-left:50%;"></i>
                </div>
                <ul class="list-style-none">
                    <li class="d-flex no-block card-body">
                        <div class="col-lg-12">
                            <span class="text-muted">Total Nilai Sampah</span><br>
                            <span class="text-muted size-30">Rp </span><span class="text-muted font-bold size-33">159.030.000</span>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body border-top">
                        <div class="col-12">
                            <span class="text-muted">Total Berat</span>
                            <br>
                            <span class="text-muted font-bold size-33">2.051 </span><span class="text-muted size-30">kg</span>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body border-top">
                        <div>
                            <span class="text-muted">Jenis Sampah</span>
                        </div>
                    </li>
                    @for($i = 0; $i<4; $i++)
                        <li class="d-flex no-block card-body no-padding">
                            <div class="width-25">
                                <span class="text-muted">Plastik</span>
                            </div>
                            <div>
                                <div class="tetx-right">
                                    <span class="text-muted font-bold">800 Kg</span>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <div class="tetx-right">
                                    <span class="text-muted font-bold">Rp40.588.000</span>
                                </div>
                            </div>
                        </li>
                    @endfor
                    <li class="d-flex no-block card-body no-padding">
                        <div class="width-25">
                            <span class="text-muted">Elektronik</span>
                        </div>
                        <div>
                            <div class="tetx-right">
                                <span class="text-muted font-bold">800 Kg</span>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <div class="tetx-right">
                                <span class="text-muted font-bold">Rp40.588.000</span>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body no-padding">
                        <div class="width-25">
                            <span class="text-muted">Lainnya</span>
                        </div>
                        <div>
                            <div class="tetx-right">
                                <span class="text-muted font-bold">123456 Kg</span>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <div class="tetx-right">
                                <span class="text-muted font-bold">Rp40.588.000</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- column -->
        <!-- column -->
        <div class="col-lg-4">
            <!-- card new -->
            <div class="card">
                <div class="card-body">
                    <span class="badge badge-success">Desember</span>
                    <i class="m-r-10 mdi mdi-folder-download size-33" style="padding-left:50%;"></i>
                </div>
                <ul class="list-style-none">
                    <li class="d-flex no-block card-body">
                        <div class="col-lg-12">
                            <span class="text-muted">Total Nilai Sampah</span><br>
                            <span class="text-muted size-30">Rp </span><span class="text-muted font-bold size-33">159.030.000</span>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body border-top">
                        <div class="col-12">
                            <span class="text-muted">Total Berat</span>
                            <br>
                            <span class="text-muted font-bold size-33">2.051 </span><span class="text-muted size-30">kg</span>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body border-top">
                        <div>
                            <span class="text-muted">Jenis Sampah</span>
                        </div>
                    </li>
                    @for($i = 0; $i<4; $i++)
                        <li class="d-flex no-block card-body no-padding">
                            <div class="width-25">
                                <span class="text-muted">Plastik</span>
                            </div>
                            <div>
                                <div class="tetx-right">
                                    <span class="text-muted font-bold">800 Kg</span>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <div class="tetx-right">
                                    <span class="text-muted font-bold">Rp40.588.000</span>
                                </div>
                            </div>
                        </li>
                    @endfor
                    <li class="d-flex no-block card-body no-padding">
                        <div class="width-25">
                            <span class="text-muted">Elektronik</span>
                        </div>
                        <div>
                            <div class="tetx-right">
                                <span class="text-muted font-bold">800 Kg</span>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <div class="tetx-right">
                                <span class="text-muted font-bold">Rp40.588.000</span>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex no-block card-body no-padding">
                        <div class="width-25">
                            <span class="text-muted">Lainnya</span>
                        </div>
                        <div>
                            <div class="tetx-right">
                                <span class="text-muted font-bold">123456 Kg</span>
                            </div>
                        </div>
                        <div class="ml-auto">
                            <div class="tetx-right">
                                <span class="text-muted font-bold">Rp40.588.000</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
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

    {{--<script src="https://www.gstatic.com/firebasejs/5.8.2/firebase.js"></script>--}}
    <!-- Firebase App is always required and must be first -->
    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js"></script>

    <!-- Add additional services that you want to use -->
    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js"></script>

    <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyAiQhyz6woWzHMDyxAR1UM2lmi-pPcbnKc",
            authDomain: "go-4-0-waste.firebaseapp.com",
            databaseURL: "https://go-4-0-waste.firebaseio.com",
            projectId: "go-4-0-waste",
            storageBucket: "go-4-0-waste.appspot.com",
            messagingSenderId: "12749329351"
        };
        firebase.initializeApp(config);

        const messaging = firebase.messaging();

        messaging.requestPermission()
            .then(function() {
                console.log('Notification permission granted.');
                return messaging.getToken();
            })
            .then(function(token) {
                alert(token);
                console.log(token); // Display user token
            })
            .catch(function(err) { // Happen if user deney permission
                console.log('Unable to get permission to notify.', err);
            });

        messaging.onMessage(function(payload){
            alert(payload);
            console.log('onMessage',payload);
        })

    </script>
@endsection