@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h1>DAFTAR ADMIN</h1>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.admin-users.create') }}" class="btn btn-success" style="cursor: pointer;">
                            <i class="fas fa-plus text-white"></i>
                            <br/>
                            <span>TAMBAH</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="user-admin" class="table table-striped table-bordered nowrap w-100">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Nama Lengkap</th>
                                    <th>Super Admin</th>
                                    <th>Waste Processor</th>
                                    <th>Role</th>
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
            responsive: false,
            pageLength: 10,
            ajax: '{!! route('datatables.admin_users') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'email', name: 'email', class: 'text-left'},
                { data: 'name', name: 'name', class: 'text-center', orderable: false, searchable: false},
                { data: 'superadmin', name: 'superadmin', class: 'text-center', orderable: false, searchable: false},
                { data: 'waste_bank', name: 'waste_bank', class: 'text-center', orderable: false, searchable: false},
                { data: 'role', name: 'role', class: 'text-center', orderable: false, searchable: false},
                { data: 'status', name: 'status', class: 'text-center', orderable: false, searchable: false},
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
    @include('partials._deletejs', ['routeUrl' => 'admin.admin-users.destroy', 'redirectUrl' => 'admin.admin-users.index'])
@endsection
