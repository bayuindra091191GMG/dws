@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h3>DAFTAR PETUGAS KEBERSIHAN</h3>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.wastecollectors.create') }}" class="btn btn-success">
                            <i class="fas fa-plus text-white"></i>
                            <br/>
                            <span>TAMBAH</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="waste-collectors" class="table table-striped table-bordered nowrap w-100">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Nama</th>
                                    <th>No KTP</th>
                                    <th>No Handphone</th>
                                    <th>Pengolahan Sampah</th>
                                    <th>Status</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Dibuat Pada</th>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $('#waste-collectors').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            pageLength: 10,
            ajax: '{!! route('datatables.waste-collectors') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'email', name: 'email'},
                { data: 'name', name: 'name', orderable: false, searchable: false},
                { data: 'identity_number', name: 'identity_number'},
                { data: 'phone', name: 'phone', class: 'text-center'},
                { data: 'waste_bank', name: 'waste_bank', class: 'text-center', orderable: false, searchable: false},
                { data: 'status', name: 'status', class: 'text-center'},
                { data: 'created_by', name: 'created_by', class: 'text-center', orderable: false, searchable: false},
                { data: 'created_at', name: 'created_at', class: 'text-center',
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
    @include('partials._deletejs', ['routeUrl' => 'admin.wastecollectors.destroy', 'redirectUrl' => 'admin.wastecollectors.index'])
@endsection
