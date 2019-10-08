@extends('layouts.frontend')

@section('title')
    <title>DWS - RESET KATA SANDI</title>
@endsection

@section('content')

    {{-- <section>
        <div class="container">
            <div class="row my-4">
                <div class="col-12">
                    <img src="{{ asset('images/landing/logo.png') }}" alt="harusnya logo" width="100px">
                </div>
            </div>
        </div>
    </section> --}}

    <div class="container" style="margin-top: 200px;">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 col-12">
                {{ Form::open(['route'=>['forgot_password.reset'],'method' => 'post','id' => 'general-form']) }}
                    @if(count($errors))
                        <div class="form-group">
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
                    @endif
                    <div class="form-group">
                        <label for="password">Kata Sandi Baru</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Konfirmasi Kata Sandi</label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                    </div>
                    <input type="hidden" id="email" name="email" value="{{ $email }}">
                    <input type="hidden" id="token" name="token" value="{{ $token }}">
                    <button type="submit" class="btn btn-primary">GANTI</button>
                {{ Form::close() }}
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>

@endsection
@section('styles')
@endsection
