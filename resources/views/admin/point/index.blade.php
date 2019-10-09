@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body"><div class="row">
                    <div class="col-12">
                        <h1>DAFTAR PENGGUNAAN POIN</h1>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="user" class="table table-striped table-bordered nowrap w-100">
                                <thead>
                                <tr>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">No Transaksi</th>
                                    <th class="text-center">Nama User</th>
                                    <th class="text-center">Tipe Transaksi</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Saldo Terakhir</th>
                                    <th class="text-center">Deskripsi</th>
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
            responsive: false,
            pageLength: 10,
            ajax: '{!! route('datatables.points') !!}',
            order: [ [0, 'desc'] ],
            columns: [
                { data: 'date', name: 'date', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'transaction_no', name: 'transaction_no', class: 'text-center'},
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'type', name: 'type', orderable: false, searchable: false, class: 'text-center'},
                { data: 'amount', name: 'amount', class: 'text-right'},
                { data: 'saldo', name: 'saldo', class: 'text-right'},
                { data: 'description', name: 'description', class: 'text-left'},
                { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            {{--language: {--}}
                {{--url: "{{ asset('indonesian.json') }}"--}}
            {{--}--}}
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
