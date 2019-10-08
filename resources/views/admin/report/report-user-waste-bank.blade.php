@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3>LAPORAN SUMBER SAMPAH</h3>
                    </div>
                </div>

                {{ Form::open(['route'=>['admin.user_waste_bank.report.submit'],'method' => 'post','id' => 'general-form']) }}

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body b-b">
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
                                                <label class="form-label" for="waste_bank">Pengolahan Sampah</label>
                                                <select id="waste_bank" name="waste_bank" class="form-control">
                                                    @if(!empty($adminWasteBank))
                                                        <option value="{{ $adminWasteBank->id }}">{{ $adminWasteBank->name }}</option>
                                                    @else
                                                        <option value="-1">Semua</option>
                                                        @foreach($wasteBanks as $wasteBank)
                                                            <option value="{{ $wasteBank->id }}">{{ $wasteBank->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-11 col-sm-11 col-xs-12" style="margin: 3% 0 3% 0;">
                                    <input type="submit" class="btn btn-success" value="UNDUH LAPORAN">
                                </div>
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
@endsection

@section('scripts')
@endsection
