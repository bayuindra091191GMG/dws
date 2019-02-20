@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Waste Collectors</h2>
                <div class="ml-auto text-right">
                    <a href="{{ route('admin.wastecollectors.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
                @include('partials.admin._messages')
                <table id="waste-collectors" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Nama</th>
                        <th>No KTP</th>
                        <th>No Handphone</th>
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
    @include('partials._delete')
@endsection

@section('styles')
    <link href="{{ asset('css/datatables.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $('#waste-collectors').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: '{!! route('datatables.waste-collectors') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'email', name: 'email', class: 'text-center'},
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'identity_number', name: 'identity_number', class: 'text-center'},
                { data: 'phone', name: 'phone', class: 'text-center'},
                { data: 'status', name: 'status', class: 'text-center'},
                { data: 'created_by', name: 'created_by', class: 'text-center'},
                { data: 'created_at', name: 'created_at', class: 'text-center'},
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
    @include('partials._deleteJs', ['routeUrl' => 'admin.wastecollectors.destroy', 'redirectUrl' => 'admin.wastecollectors.index'])
@endsection