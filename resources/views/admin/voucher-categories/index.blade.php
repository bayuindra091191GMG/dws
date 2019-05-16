@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1>DAFTAR KATEGORI VOUCHER</h1>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.voucher-categories.create') }}" class="btn btn-success" style="cursor: pointer;">
                            <i class="fas fa-plus text-white"></i>
                            <br/>
                            <span>TAMBAH</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="table_general" class="table table-striped table-bordered dt-responsive" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Nama</th>
{{--                                <th>Gambar</th>--}}
                                <th>Dibuat Pada</th>
                                <th>Dibuat Oleh</th>
                                <th>Diubah Pada</th>
                                <th>Diubah Oleh</th>
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
            pageLength: 25,
            ajax: '{!! route('datatables.voucher-categories') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'name', name: 'name', class: 'text-center'},
                // { data: 'img_path', name: 'img_path', class: 'text-center'},
                { data: 'created_at', name: 'created_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'created_by', name: 'created_by', class: 'text-center'},
                { data: 'updated_at', name: 'updated_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'updated_by', name: 'updated_by', class: 'text-center'},
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
    @include('partials._deletejs', ['routeUrl' => 'admin.voucher-categories.destroy', 'redirectUrl' => 'admin.voucher-categories.index'])
@endsection