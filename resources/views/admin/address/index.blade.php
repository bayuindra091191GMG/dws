@extends('layouts.admin')

@section('content')
    <header class="blue accent-3 relative nav-sticky">
        <div class="container-fluid text-white">
            <div class="row p-t-b-10 ">
                <div class="col">
                    <h4> <i class="icon-table"></i> Store Addresses</h4>
                </div>
            </div>
        </div>
    </header>

    <div class="content-wrapper animatedParent animateOnce">
        <div class="container">
            <section class="paper-card">
                <div class="row">
                    <table class="table cell-vertical-align-middle  table-responsive mb-4">
                        <tbody>
                        <tr class="no-b">
                            <td>
                                <a href="{{ route('admin.store-address.create') }}" class="btn btn-outline-primary btn-lg btn-block">
                                    <i class="icon icon-plus"></i> Add
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="col-lg-12">
                        @include('partials.admin._messages')
                        <table id="store-address" class="table table-striped table-bordered dt-responsive nowrap" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Primary</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>District</th>
                                <th>Postal Code</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Updated By</th>
                                <th>Updated At</th>
                                {{--<th></th>--}}
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </section>
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
        $('#store-address').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: '{!! route('datatables.store-addresses') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'description', name: 'description', class: 'text-center'},
                { data: 'primary', name: 'primary', class: 'text-center'},
                { data: 'province', name: 'province', class: 'text-center'},
                { data: 'city', name: 'city', class: 'text-center'},
                { data: 'district', name: 'district', class: 'text-center'},
                { data: 'postal_code', name: 'postal_code', class: 'text-center'},
                { data: 'created_by', name: 'created_by', class: 'text-center'},
                { data: 'created_at', name: 'created_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'updated_by', name: 'updated_by', class: 'text-center'},
                { data: 'updated_at', name: 'updated_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                // { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
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
    @include('partials._deleteJs', ['routeUrl' => 'admin.store-address.destroy', 'redirectUrl' => 'admin.store-address.index'])
@endsection