@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1>DAFTAR VOUCHER</h1>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success" style="cursor: pointer;">
                            <i class="fas fa-plus text-white"></i>
                            <br/>
                            <span>TAMBAH</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="table_general" class="table table-striped table-bordered dt-responsive" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Deskripsi</th>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                    <th>Poin yang dibutuhkan</th>
                                    <th>Tanggal Berlaku</th>
                                    <th>Tanggal Kedaluarsa</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                    <th>Dibuat Oleh</th>
                                    <th></th>
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
            ajax: '{!! route('datatables.vouchers') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'code', name: 'code', class: 'text-center'},
                { data: 'description', name: 'description', class: 'text-center'},
                { data: 'category', name: 'category', class: 'text-center'},
                { data: 'quantity', name: 'quantity', class: 'text-center'},
                { data: 'required_point', name: 'required_point', class: 'text-center'},
                { data: 'start_date', name: 'start_date', class: 'text-center'},
                { data: 'finish_date', name: 'finish_date', class: 'text-center'},
                { data: 'status', name: 'status', class: 'text-center'},
                { data: 'created_at', name: 'created_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'created_by', name: 'created_by', class: 'text-center'},
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
