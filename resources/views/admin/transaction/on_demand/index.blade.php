@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('partials.admin._messages')
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1>Daftar Transaksi On Demand</h1>
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
                        <table id="user" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
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
                            <tbody></tbody>
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
    <script>
        $('#user').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: '{!! route('datatables.on_demand.transactions') !!}',
            order: [ [0, 'desc'] ],
            columns: [
                { data: 'date', name: 'date', class: 'text-center',
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'transaction_no', name: 'transaction_no', class: 'text-center'},
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'category', name: 'category', orderable: false, searchable: false, class: 'text-center'},
                { data: 'total_weight', name: 'total_weight', class: 'text-right',
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return data.toLocaleString(
                                "de-DE",
                                {minimumFractionDigits: 2}
                            );
                        }
                        return data;
                    }
                },
                { data: 'total_price', name: 'total_price', class: 'text-right',
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return data.toLocaleString(
                                "de-DE",
                                {minimumFractionDigits: 2}
                            );
                        }
                        return data;
                    }
                },
                { data: 'waste_bank', name: 'waste_bank', orderable: false, searchable: false, class: 'text-center'},
                { data: 'waste_collector', name: 'waste_collector', orderable: false, searchable: false, class: 'text-center'},
                { data: 'status', name: 'status', class: 'text-center'},
                { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            language: {
                url: "{{ asset('indonesian.json') }}"
            }
        });

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