@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1> PENGGUNAAN VOUCHER</h1>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="table_general" class="table table-striped table-bordered nowrap w-100">
                                <thead>
                                <tr>
                                    <th>Nama Pengguna</th>
                                    <th>Code Voucher</th>
                                    <th>Tanggal Redeem</th>
                                    <th>Status Voucher</th>
                                    <th>Tanggal Penggunaan</th>
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
    @include('partials._delete')
@endsection

@section('styles')
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $('#table_general').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            pageLength: 10,
            ajax: '{!! route('datatables.voucher.users') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'name', name: 'name', class: 'text-center', orderable: false, searchable: false},
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
                }
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
