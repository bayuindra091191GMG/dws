@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h3>DAFTAR PENGOLAHAN SAMPAH</h3>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.waste-banks.create') }}" class="btn btn-success" style="cursor: pointer;">
                            <i class="fas fa-plus text-white"></i>
                            <br/>
                            <span>KATEGORI DWS</span>
                        </a>
                        <a href="{{ route('admin.waste-banks.create.masaro') }}" class="btn btn-success" style="cursor: pointer;">
                            <i class="fas fa-plus text-white"></i>
                            <br/>
                            <span>KATEGORI MASARO</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="waste-bank" class="table table-striped table-bordered dt-responsive">
                                <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kategori/Jenis</th>
                                    <th>Alamat</th>
                                    <th>Hari Buka</th>
                                    <th>Jam Buka</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>PIC</th>
                                    <th>Nomor Ponsel</th>
                                    <th>Kota</th>
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
        $('#waste-bank').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            pageLength: 10,
            ajax: '{!! route('datatables.waste-banks') !!}',
            order: [ [0, 'asc'] ],
            columns: [
                { data: 'name', name: 'name', class: 'text-center'},
                { data: 'type', name: 'type', class: 'text-center'},
                { data: 'address', name: 'address', class: 'text-center'},
                { data: 'open_days', name: 'open_days', class: 'text-center', orderable: false, searchable: false },
                { data: 'open_hours', name: 'open_hours', class: 'text-center', orderable: false, searchable: false },
                { data: 'latitude', name: 'latitude', class: 'text-center'},
                { data: 'longitude', name: 'longitude', class: 'text-center'},
                { data: 'pic', name: 'pic', class: 'text-center', orderable: false, searchable: false },
                { data: 'phone', name: 'phone', class: 'text-center'},
                { data: 'city', name: 'city', class: 'text-center', orderable: false, searchable: false },
                { data: 'status', name: 'status', class: 'text-center', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at', class: 'text-center', orderable: false, searchable: false,
                    render: function ( data, type, row ){
                        if ( type === 'display' || type === 'filter' ){
                            return moment(data).format('DD MMM YYYY');
                        }
                        return data;
                    }
                },
                { data: 'created_by', name: 'created_by', class: 'text-center', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'}
            ],
            language: {
                url: "{{ asset('indonesian.json') }}"
            }
        });

        // $(document).on('click', '.delete-modal', function(){
        //     $('#deleteModal').modal({
        //         backdrop: 'static',
        //         keyboard: false
        //     });
        //
        //     $('#deleted-id').val($(this).data('id'));
        // });
    </script>
{{--    @include('partials._deletejs', ['routeUrl' => 'admin.waste-banks.destroy', 'redirectUrl' => 'admin.waste-banks.index'])--}}
@endsection
