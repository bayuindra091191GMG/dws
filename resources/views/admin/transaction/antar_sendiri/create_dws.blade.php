@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>BUAT TRANSAKSI BARU ANTAR SENDIRI KATEGORI DWS</h3>
                    </div>
                </div>

                {{ Form::open(['route'=>['admin.transactions.antar_sendiri.store'],'method' => 'post','id' => 'general-form']) }}

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">
                                <!-- Input -->
                                <div class="body">

                                    @if(count($errors))
                                        <div class="col-md-12">
                                            <div class="form-group form-float form-group-lg">
                                                <div class="form-line">
                                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <ul>
                                                            @foreach($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="code">Nomor Transaksi</label>
                                                <input id="code" name="code" type="text" class="form-control" value="{{ $code }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label">Jenis Transaksi</label>
                                                <input type="text" class="form-control" value="Antar Sendiri" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label">Pengolahan Sampah</label>
                                                <input type="text" class="form-control" value="{{ $wasteBank }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="date">Tanggal *</label>
                                                <input id="date" name="date" type="text" class="form-control" autocomplete="off" value="{{ $dateToday }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="notes">Catatan</label>
                                                <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="text-right w-100">
                                            <a class="btn btn-success" id="btn-add-row" style="color: #fff !important; cursor: pointer;" onclick="addRow();">TAMBAH KATEGORI</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <table id="category_table" class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Berat (kg)</th>
                                            <th scope="col">Harga</th>
                                            <th scope="col">Tindakan</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {{--<tr>--}}
                                        {{--<th scope="row">1</th>--}}
                                        {{--<td>Mark</td>--}}
                                        {{--<td>Otto</td>--}}
                                        {{--<td>@mdo</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                        {{--<th scope="row">2</th>--}}
                                        {{--<td>Jacob</td>--}}
                                        {{--<td>Thornton</td>--}}
                                        {{--<td>@fat</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                        {{--<th scope="row">3</th>--}}
                                        {{--<td>Larry</td>--}}
                                        {{--<td>the Bird</td>--}}
                                        {{--<td>@twitter</td>--}}
                                        {{--</tr>--}}
                                        </tbody>
                                    </table>
                                </div>

                                <input type="hidden" name="category_type" value="1"/>

                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <a href="{{ route('admin.transactions.antar_sendiri.index') }}" class="btn btn-danger">BATAL</a>
                                    <input type="submit" class="btn btn-success" value="SIMPAN">
                                </div>
                            </div>
                            <!-- #END# Input -->
                        </div>
                    </div>
                </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>


@endsection

@section('styles')
    <link href="{{ asset('css/select2-bootstrap4.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style>
        .select2-container--default .select2-search--dropdown::before {
            content: "";
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{ asset('backend/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.2.0"></script>
    <script>
        var categories = [];
        @foreach($wasteCategories as $category)
            categories.push({ id: '{{ $category->id }}', name: '{{ $category->name }}'})
        @endforeach

        jQuery('#date').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd M yyyy"
        });

        var i = 0;

        function addRow(){
            var sbAdd = "<tr id='row_" + i + "'>";
            sbAdd += "<td><select id='category_" + i + "' name='categories[]' class='form-control'>";
            sbAdd += "<option value='-1'> - Pilih Kategori - </option>";

            if(categories.length > 0){
                for(var j = 0; j< categories.length; j++){
                    sbAdd += "<option value='" + categories[j].id + "'>" + categories[j].name + "</option>";
                }
            }

            sbAdd += "<select/></td>";
            sbAdd += "<td><input type='text' id='weight_" + i + "' name='weights[]' class='form-control text-right' autocomplete='off' /></td>";
            sbAdd += "<td><input type='text' id='price_" + i + "' name='prices[]' class='form-control text-right' autocomplete='off' /></td>";
            sbAdd += "<td class='text-center'><a class='btn btn-danger' style='cursor: pointer;' onclick='deleteRow(" + i + ")'><i class='fas fa-minus-circle text-white'></a></td>";

            $('#category_table').append(sbAdd);

            new AutoNumeric('#weight_' + i, {
                minimumValue: '0',
                maximumValue: '999999',
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                decimalPlaces: 0,
                modifyValueOnWheel: false
            });

            new AutoNumeric('#price_' + i, {
                minimumValue: '0',
                maximumValue: '9999999999',
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                decimalPlaces: 0,
                modifyValueOnWheel: false
            });

            i++;
        }

        function deleteRow(rowIdx){
            $('#row_' + rowIdx).remove();
        }

    </script>
@endsection