@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            @include('partials.admin._messages')
            <div class="card-body">
                <h2 class="card-title m-b-0">Transaksi</h2>
                <table id="user" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>Nama User</th>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th>Berat Total</th>
                        <th>Harga Total</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
            ajax: '{!! route('datatables.transactions') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'transaction_no', name: 'transaction_no', class: 'text-center'},
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'type', name: 'type', class: 'text-center'},
                { data: 'category', name: 'category', class: 'text-center'},
                { data: 'total_weight', name: 'total_weight', class: 'text-center'},
                { data: 'total_price', name: 'total_price', class: 'text-center'},
                { data: 'status', name: 'status', class: 'text-center'},
                { data: 'created_at', name: 'created_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
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
    @include('partials._deleteJs', ['routeUrl' => 'admin.users.destroy', 'redirectUrl' => 'admin.users.index'])
@endsection