@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>UBAH TRANSAKSI PENJEMPUTAN RUTIN KATAEGORI MASARO</h3>
                    </div>
                </div>

                {{ Form::open(['route'=>['admin.transactions.penjemputan_rutin.update', $header->id],'method' => 'post','id' => 'general-form']) }}

                <div class="row">
                    <div class="col">
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
                                                <input id="code" name="code" type="text" class="form-control" value="{{ $header->transaction_no }}" readonly>
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
                                                <label class="form-label" for="date">Tanggal *</label>
                                                <input id="date" name="date" type="text" class="form-control" autocomplete="off" value="{{ $date }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group form-float form-group-lg">
                                            <div class="form-line">
                                                <label class="form-label" for="notes">Catatan</label>
                                                <textarea id="notes" name="notes" class="form-control" rows="3">{{ $header->notes }}</textarea>
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
                                        @php( $idx = 0 )
                                        @foreach($header->transaction_details as $detail)
                                            <tr id="row_{{ $idx }}">
                                                <td>
                                                    <select id="category_{{ $idx }}" name="categories[]" class="form-control">
                                                        <option value="{{ $detail->masaro_category_id. '#'. $detail->masaro_waste_category_data->price }}" selected>{{ $detail->masaro_waste_category_data->name }}</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" id="weight_{{ $idx }}" name="weights[]" class="form-control text-right"/></td>
                                                <td class="text-right">
                                                    <span id="price_str_{{ $idx }}">{{ $detail->price_string }}</span>
                                                    <input type="hidden" id="price_{{ $idx }}" name="prices[]" value="{{ $detail->price }}"/>
                                                </td>
                                                <td class="text-center"><a class="btn btn-danger" style="cursor: pointer;" onclick="deleteRow('{{ $idx }}')"><i class="fas fa-minus-circle text-white"></i></a></td>
                                            </tr>
                                            @php( $idx++ )
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <input type="hidden" name="category_type" value="2"/>

                                <div class="col-md-11 col-sm-11 col-xs-12">
                                    <a href="{{ route('admin.transactions.penjemputan_rutin.show', ['id' => $header->id]) }}" class="btn btn-danger">BATAL</a>
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

        // Set autonumeric each row
        @php( $numericIdx = 0 )
        @foreach($header->transaction_details as $detail)
            new AutoNumeric('#weight_{{ $numericIdx }}', '{{ $detail->weight_kg }}', {
                minimumValue: '0',
                maximumValue: '999999',
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                decimalPlaces: 4,
                modifyValueOnWheel: false,
                allowDecimalPadding: false,
            });

            $('#category_{{ $numericIdx }}').select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Kategori Sampah Masaro - '
                },
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('select.masaro-categories.extended') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#category_{{ $numericIdx }}').on('select2:select', function (e) {
                let data = e.params.data;
                let arr = data.id.split('#');

                $('#price_{{ $numericIdx }}').val(arr[1]);
                $('#price_str_{{ $numericIdx}}').html(rupiahFormat(arr[1]));
            });

            @php( $numericIdx++ )
        @endforeach

        var i = parseInt("{{ $idx }}");

        // Add new category entry
        function addRow(){
            let bufferIdx = i;
            var sbAdd = "<tr id='row_" + bufferIdx + "'>";
            sbAdd += "<td><select id='category_" + bufferIdx + "' name='categories[]' class='form-control'>";
            sbAdd += "<option value='-1'> - Pilih Kategori - </option>";

            if(categories.length > 0){
                for(var j = 0; j< categories.length; j++){
                    sbAdd += "<option value='" + categories[j].id + "'>" + categories[j].name + "</option>";
                }
            }

            sbAdd += "<select/></td>";
            sbAdd += "<td><input type='text' id='weight_" + bufferIdx + "' name='weights[]' class='form-control text-right' /></td>";
            sbAdd += "<td class='text-right'><span id='price_str_" + bufferIdx + "'></span><input type='hidden' id='price_" + bufferIdx + "' name='prices[]'/></td>";
            sbAdd += "<td class='text-center'><a class='btn btn-danger' style='cursor: pointer;' onclick='deleteRow(" + bufferIdx + ")'><i class='fas fa-minus-circle text-white'></i></a></td>";

            $('#category_table').append(sbAdd);

            window['weight_' + bufferIdx] = new AutoNumeric('#weight_' + bufferIdx, {
                minimumValue: '0',
                maximumValue: '999999',
                digitGroupSeparator: '.',
                decimalCharacter: ',',
                decimalPlaces: 4,
                modifyValueOnWheel: false,
                allowDecimalPadding: false,
            });

            $('#category_' + bufferIdx).select2({
                placeholder: {
                    id: '-1',
                    text: ' - Pilih Kategori Sampah Masaro - '
                },
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('select.masaro-categories.extended') }}',
                    dataType: 'json',
                    data: function (params) {
                        return {
                            q: $.trim(params.term)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    }
                }
            });

            $('#category_' + bufferIdx).on('select2:select', function (e) {
                let data = e.params.data;
                let arr = data.id.split('#');

                window['weight_' + bufferIdx].set(1);
                $('#price_' + bufferIdx).val(arr[1]);
                $('#price_str_' + bufferIdx).html(rupiahFormat(arr[1]));
            });

            i++;
        }

        function deleteRow(rowIdx){
            $('#row_' + rowIdx).remove();
        }

        function rupiahFormat(nStr) {
            return nStr.toLocaleString(
                "de-DE"
            );
        }

    </script>
@endsection
