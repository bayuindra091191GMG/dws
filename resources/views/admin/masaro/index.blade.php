@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <h2 class="card-title m-b-0">Masaro Waste Category</h2>
                <div class="ml-auto text-right">
                    <a href="{{ route('admin.masaro-wastes.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
                </div>
                @include('partials.admin._messages')
                <table id="dws-waste" class="table table-striped table-bordered dt-responsive" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Created By</th>
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
        $('#dws-waste').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: '{!! route('datatables.masaro-wastes') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'price', name: 'price', class: 'text-center'},
                { data: 'description', name: 'description', class: 'text-center'},
                { data: 'image', name: 'image', class: 'text-center'},
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
    @include('partials._deletejs', ['routeUrl' => 'admin.masaro-wastes.destroy', 'redirectUrl' => 'admin.masaro-wastes.index'])
@endsection