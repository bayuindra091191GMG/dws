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
            <div class="col-md-4 col-12 text-center">
                <h3>Reset Kata Sandi telah berhasil untuk akun {{ $email }}</h3>
                <span>Silahkan Login kembali ke aplikasi dengan kata sandi baru.</span>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>

@endsection
@section('styles')
@endsection
