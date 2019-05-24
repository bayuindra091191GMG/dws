@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>DAFTAR OTORISASI MENU</h3>
                        @include('partials.admin._messages')
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table id="permission-table" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th class="text-center">Level Akses</th>
                                <th class="text-center">Jumlah Menu yang Dapat Diakses</th>
                                <th class="text-center">Tindakan</th>
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
        $(function() {
            $('#permission-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: '{!! route('datatables.permission-menus') !!}',
                columns: [
                    { data: 'role', name: 'role', class: 'text-center' },
                    { data: 'permission', name: 'permission', class: 'text-center' },
                    { data: 'action', name:'action', class: 'text-center' }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Indonesian-Alternative.json"
                }
            });
        });
    </script>
@endsection
