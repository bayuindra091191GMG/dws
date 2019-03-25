@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Daftar Penggunaan Voucher</h2>
                {{--<div class="ml-auto text-right">--}}
                    {{--<a href="{{ route('admin.vouchers.create') }}" class="btn btn-success">--}}
                        {{--<i class="fas fa-plus"></i> Tambah--}}
                    {{--</a>--}}
                {{--</div>--}}
                @include('partials.admin._messages')
                <table id="user-admin" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Code Voucher</th>
                        <th>Tanggal Redeem</th>
                        <th>Status Voucher</th>
                        <th>Tanggal Penggunaan</th>
                        <th>Tanggal Pembuatan</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    @include('partials._delete')
@endsection

@section('styles')
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $('#user-admin').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: '{!! route('datatables.voucher.users') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'code', name: 'code', class: 'text-center'},
                { data: 'redeem_at', name: 'redeem_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'is_used', name: 'is_used', class: 'text-center'},
                { data: 'used_at', name: 'used_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
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
        });

        $(document).on('click', '.delete-modal', function(){
            $('#deleteModal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $('#deleted-id').val($(this).data('id'));
        });
    </script>
    @include('partials._deletejs', ['routeUrl' => 'admin.vouchers.destroy', 'redirectUrl' => 'admin.vouchers.index'])
@endsection