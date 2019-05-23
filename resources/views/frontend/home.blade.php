@extends('layouts.frontend')

@section('content')

<section>
    <div class="container">
        <div class="row my-4">
            <div class="col-12">
                <img src="{{ asset('images/landing/logo.png') }}" alt="harusnya logo" width="100px">
            </div>
        </div>
    </div>
</section>

<section class="d-none d-md-block">
    <div class="w-100 img-banner-responsive" style="background-image: url('{{ asset('images/landing/heroimage.png') }}');
	background-repeat: no-repeat;
	background-position: center;
	background-size: cover;">
        <div class="col-1"></div>
        <div class= "col-5" style="padding-left:8%;padding-top:4%">
            <div class="pt-5 mt-5">
                <span class="font-weight-bolder open-sans-regular" style="font-size: 50px;">Go 4.0 Waste</span>
            </div>
            <p class="font-weight-bolder open-sans-regular" style="font-size: 18px;">
                Inovasi pengelolaan sampah terpadu
            </p>

            <div class="pt-5-custom">
                <span class = "open-sans-regular" style="font-size: 13px;">Tersedia di:</span>
                <div>
                    {{-- <a href="#"><img src="{{ asset('images/landing/apple-badge.png') }}"></a> --}}
                    <a href="#"><img src="{{ asset('images/landing/google-badge.png') }}"></a>
                </div>
            </div>
        </div>
        <div class="col-6"></div>
    </div>
</section>
<!-- banner mobile start -->

<section class= "d-block d-md-none">
    <div class="mb-5 mt-2">
        <div class="w-100 img-banner-responsive2" style="background-image: url('{{ asset('images/landing/heroimage-2.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            margin-bottom: -130px;">
            <div class= "col-12 text-center">
                <div class="pt-3">
                    <span class="font-weight-bolder open-sans-regular" style="font-size: 40px;">Go 4.0 Waste</span>
                </div>
                <p class="font-weight-bolder open-sans-regular" style="font-size: 18px;">
                    Inovasi pengelolaan sampah terpadu
                </p>

                <div class="text-center py-3">

                    <span class="open-sans-regular" style="font-size: 15px;">Tersedia di:</span>
                    <div class="row">
                        <div class= "col-12">
                            {{-- <a href="#"><img src="{{ asset('images/landing/apple-badge.png') }}" style="width: 120px;"></a> --}}
                            <a href="#"><img src="{{ asset('images/landing/google-badge.png') }}" style="width: 120px;"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner mobile end -->
<section class= "mt-5">
    <div class="container">
        <div class="row">
            <div class="col-4 d-none d-md-block">
                <img src="{{ asset('images/landing/sampah-1.png') }}" alt="harusnya sampah-1" width="100px">
                <p class="bold py-4 font24">Pilah sampah Anda</p>
                <p class="font20">Dengan anda mengumpulkan dan memilah sampah berdasarkan kategori, Anda telah berperan untuk mewujudkan lingkungan bersih dan sehat.</p>
            </div>
            <div class="col-4 d-none d-md-block">
                <img src="{{ asset('images/landing/sampah-2.png') }}" alt="harusnya sampah-2" width="100px">
                <p class="bold py-4 font24">Setor sampah Anda</p>
                <p class="font20">Anda dapat memilih jadwal penjemputan atau mengantar sampah ke fasilitas pengolahan sampah terdekat dari lokasi Anda.</p>
            </div>
            <div class="col-4 d-none d-md-block">
                <img src="{{ asset('images/landing/sampah-3.png') }}" alt="harusnya sampah-3" width="100px">
                <p class="bold py-4 font24">Dapatkan poin menarik</p>
                <p class="font20">Setiap sampah yang Anda setorkan akan menghasilkan poin dan keuntungan menarik lainnya.</p>
            </div>
        </div>

        <!-- mobile start -->
        <div class="row">
            <div class="col-11 d-block d-md-none pb-5">
                <img src="{{ asset('images/landing/sampah-1.png') }}" alt="harusnya sampah-1" width="100px">
                <p class="bold pt-3 font24">Pilah sampah Anda</p>
                <p class="font-20">Dengan anda mengumpulkan dan memilah sampah berdasarkan kategori, Anda telah berperan untuk mewujudkan lingkungan bersih dan sehat.</p>
            </div>
            <div class="col-1"></div>
            <div class="col-11 d-block d-md-none pb-5">
                <img src="{{ asset('images/landing/sampah-2.png') }}" alt="harusnya sampah-2" width="100px">
                <p class="bold pt-3 font24">Setor sampah Anda</p>
                <p class="font-20">Anda dapat memilih jadwal penjemputan atau mengantar sampah ke fasilitas pengolahan sampah terdekat dari lokasi Anda.</p>
            </div>
            <div class="col-1"></div>
            <div class="col-11 d-block d-md-none pb-5">
                <img src="{{ asset('images/landing/sampah-3.png') }}" alt="harusnya sampah-3" width="100px">
                <p class="bold pt-3 font24">Dapatkan poin menarik</p>
                <p class="font-20">Setiap sampah yang Anda setorkan akan menghasilkan poin dan keuntungan menarik lainnya.</p>
            </div>
            <div class="col-1"></div>
        </div>
        <!-- mobile end -->
    </div>
</section>

<section>
    <div class="container pt-5">
        <div class="row d-none d-md-block">
            <div class="col-12 text-center">
                <p class="font-weight-bold font48">Fitur pengguna aplikasi Go 4.0 Waste</p>
                <p class="font20">Pada apllikasi pengguna Go 4.0 Waste terdapat tiga jenis fitur<br> dalam penyetoran sampah.</p>
            </div>
        </div>
        <div class="row d-block d-md-none">
            <div class="col-12">
                <h2 class="font-weight-bold">Fitur pengguna aplikasi Go 4.0 Waste</h2>
                <p>Pada apllikasi pengguna Go 4.0 Waste terdapat tiga jenis fitur dalam penyetoran sampah.</p>
            </div>
        </div>
    </div>
</section>
<section class="d-none d-md-block">
    <div class="container">
        <div class="row mt-5 pt-4 mb-3">
            <div class="col-2"></div>
            <div class="col-5">
                <p class="font-weight-bold font32">Fitur Jemput Rutin</p>
                {{-- <p class="font20">Jemput rutin adalah fitur penjemputan <br>sampah rutin sesuai dengan jadwal di<br> dalam aplikasi Anda.</p> --}}
            </div>
            <div class="col-5"></div>
        </div>
        <div class="row pb-5 mb-5">
            <div class="col-2"></div>
            <div class="col-8">
                <p class="font20">Jemput rutin adalah fitur penjemputan sampah rutin sesuai dengan<br> jadwal di dalam aplikasi Anda.</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-5"></div>
            <div class="col-5 text-center" style="margin-bottom: 63px;">
                <img src="{{ asset('images/landing/number-black-1.png') }}" alt="harusnya number-black-1" width="40px">
                <p class="font-weight-bold font24 pt-5-c">Pilih Fitur Jemput Rutin</p>
                <p class="font20">Fitur ini menyediakan pilihan jadwal <br>penjemputan rutin sampah Anda.</p>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
</section>
<section class="d-none d-md-block" style="background-color: #3dcc9c;">
    <div class="container">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-1.png') }}" alt="harusnya hp-1" width="250px" style="margin-top: -110%;">
            </div>
            <div class="col-5 text-center">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-5">
                <div class="text-center">
                    <img src="{{ asset('images/landing/number-white-2.png') }}" alt="harusnya number-white-2" width="40px">
                </div>
                <div class="text-center text-white">
                    <p class="pt-5-c font-weight-bold font24">Masukkan alamat anda</p>
                    <p class="font20">Masukkan alamat anda berada agar <br>Petugas Kebersihan dapat mengambil <br>sampah yang Anda setor.</p>
                </div>
            </div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-2.png') }}" alt="harusnya hp-2" width="250px">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-3.png') }}" alt="harusnya hp-3" width="250px">
            </div>
            <div class="col-5 text-center text-white">
                <img src="{{ asset('images/landing/number-white-3.png') }}" alt="harusnya number-white-3" width="40px">
                <p class="pt-5-c font-weight-bold font24">Cek jadwal penjemputan rutin</p>
                <p class="font20">Pastikan anda memeriksa jadwal dan <br>kategori sampah yang akan disetor <br>karena Petugas Kebersihan hanya akan <br>menerima sampah sesuai dengan jadwal <br>yang ada.</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-5">
                <div class="text-center">
                    <img src="{{ asset('images/landing/number-white-4.png') }}" alt="harusnya number-white-4" width="40px">
                </div>
                <div class="text-center text-white">
                    <p class="pt-5-c font-weight-bold font24">Scan kode QR</p>
                    <p class="font20">Petugas Kebersihan akan melakukan <br>scan kode QR Anda sebelum membawa <br>sampah yang Anda setor.</p>
                </div>
            </div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-7.png') }}" alt="harusnya hp-7" width="250px" style="margin-bottom: -80%">
            </div>
            <div class="col-2"></div>
        </div>
    </div>
</section>

<div class="container d-none d-md-block" style="margin-top: 18%;">
    <div class="row">
        <div class="col-12">
            <hr/>
        </div>
    </div>
</div>

<!-- jemput rutin mobile start -->
<section class="d-block d-md-none" style="background-color: #3dcc9c;">
    <div class="container">
        <div class="row py-3 text-center">
            <div class="col-12">
                <p class="pt-2 font-weight-bold text-white subfont">Fitur Jemput Rutin</p>
                <p class="text-white">Jemput rutin adalah fitur penjemputan sampah rutin sesuai dengan jadwal di dalam aplikasi Anda.</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-1-white-green.png') }}" alt="harusnya number-1-white-green" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Pilih Fitur Jemput Rutin</p>
                    <p class="text-white">Pada aplikasi Go 4.0 Waste, ada tiga jenis fitur penyetoran sampah. Pilih Fitur jemput rutin pada aplikasi Anda.</p>
                </div>
                <img src="{{ asset('images/landing/hp-1.png') }}" alt="harusnya hp-1" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-white-2.png') }}" alt="harusnya number-white-2" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Masukkan alamat Anda</p>
                    <p class="text-white">Masukkan alamat Anda berada agar Petugas Kebersihan dapat mengambil sampah yang anda setor.</p>
                </div>
                <img src="{{ asset('images/landing/hp-2.png') }}" alt="harusnya hp-2" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-white-3.png') }}" alt="harusnya number-white-3" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Cek jadwal penjemputan rutin</p>
                    <p class="text-white">Pastikan Anda memeriksa jadwal dan kategori sampah yang akan disetor karena Petugas Kebersihan hanya akan menerima sampah sesuai dengan jadwal yang ada.</p>
                </div>
                <img src="{{ asset('images/landing/hp-3.png') }}" alt="harusnya hp-3" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-white-4.png') }}" alt="harusnya number-white-4" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Scan kode QR</p>
                    <p class="text-white">Petugas Kebersihan akan melakukan scan kode QR Anda sebelum membawa sampah yang Anda setor.</p>
                </div>
                <img src="{{ asset('images/landing/hp-7.png') }}" alt="harusnya hp-7" width="200px" style="margin-bottom: -20%;">
            </div>
        </div>
    </div>
</section>
<!-- jemput rutin mobile end -->
<section class="mt-3 d-none d-md-block">
    <div class="container">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-4">
                <p class="font-weight-bold font32">Fitur Jemput Sekarang</p>
                {{-- <p class="font20">Jemput sekarang adalah fitur penjemputan sampah sesuai dengan <br>permintaan Anda di hari yang sama.</p> --}}
            </div>
            <div class="col-6"></div>
        </div>
        <div class="row pb-5">
            <div class="col-2"></div>
            <div class="col-8">
                <p class="font20">Jemput sekarang adalah fitur penjemputan sampah sesuai dengan <br>permintaan Anda di hari yang sama.</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-2"></div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-4.png') }}" alt="harusnya hp-4" width="250px">
            </div>
            <div class="col-5 text-center">
                <img src="{{ asset('images/landing/number-black-1.png') }}" alt="harusnya number-black-1" width="40px">
                <p class="pt-5-c font-weight-bold font24">Pilih Fitur Jemput Sekarang</p>
                <p class="font20">Fitur ini menyediakan pilihan jadwal <br>penjemputan sesuai permintaan Anda</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-5">
                <div class="text-center">
                    <img src="{{ asset('images/landing/number-black-2.png') }}" alt="harusnya number-black-2" width="40px">
                </div>
                <div class="text-center">
                    <p class="pt-5-c font-weight-bold font24">Masukkan alamat Anda</p>
                    <p class="font20">Masukkan alamat anda berada agar <br>Petugas Kebersihan dapat mengambil <br>sampah yang Anda setor.</p>
                </div>
            </div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-5.png') }}" alt="harusnya hp-5" width="250px">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-6.png') }}" alt="harusnya hp-6" width="250px">
            </div>
            <div class="col-5 text-center">
                <img src="{{ asset('images/landing/number-black-3.png') }}" alt="harusnya number-black-3" width="40px">
                <p class="pt-5-c font-weight-bold font24">Masukkan detil sampah Anda</p>
                <p class="font20">Masukkan detil sampah Anda yang akan<br> diangkut berdasarkan informasi detil<br> sampah yang sudah Anda lengkapi.</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-5">
                <div class="text-center">
                    <img src="{{ asset('images/landing/number-black-4.png') }}" alt="harusnya number-black-4" width="40px">
                </div>
                <div class="text-center">
                    <p class="pt-5-c font-weight-bold font24">Scan kode QR</p>
                    <p class="font20">Petugas Kebersihan akan melakukan <br>scan kode QR Anda sebelum membawa<br> sampah yang Anda setor.</p>
                </div>
            </div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-7.png') }}" alt="harusnya hp-7" width="250px">
            </div>
            <div class="col-2"></div>
        </div>
    </div>
</section>
<!-- jemput sekarang mobile start -->
<section class="mt-5 d-block d-md-none">
    <div class="container">
        <div class="row py-3 text-center" style="margin-top: 40%;">
            <div class="col-12">
                <p class="pt-2 font-weight-bold subfont">Fitur Jemput Sekarang</p>
                <p>Jemput sekarang adalah fitur penjemputan sampah sesuai dengan permintaan Anda di hari yang sama.</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-black-1.png') }}" alt="harusnya number-black-1" width="30px">
                    <p class="pt-2 font-weight-bold subfont">Pilih Fitur jemput Sekarang</p>
                    <p>Fitur ini menyediakan pilihan jadwal penjemputan sesuai permintaan Anda</p>
                </div>
                <img src="{{ asset('images/landing/hp-4.png') }}" alt="harusnya hp-4" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-black-2.png') }}" alt="harusnya number-black-2" width="30px">
                    <p class="pt-2 font-weight-bold subfont">Masukkan alamat anda</p>
                    <p>Masukkan alamat Anda berada agar Petugas Kebersihan dapat mengambil sampah yang anda setor.</p>
                </div>
                <img src="{{ asset('images/landing/hp-5.png') }}" alt="harusnya hp-5" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-black-3.png') }}" alt="harusnya number-black-3" width="30px">
                    <p class="pt-2 font-weight-bold subfont">Input detil sampah anda</p>
                    <p>Masukkan detil sampah Anda yang akan diangkut bersasarkan informasi detil sampah yang sudah Anda lengkapi.</p>
                </div>
                <img src="{{ asset('images/landing/hp-6.png') }}" alt="harusnya hp-6" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-black-4.png') }}" alt="harusnya number-black-4" width="30px">
                    <p class="pt-2 font-weight-bold subfont">Scan kode QR</p>
                    <p>Petugas Kebersihan akan melakukan scan kode QR sebelum membawa sampah yang Anda setor.</p>
                </div>
                <img src="{{ asset('images/landing/hp-7.png') }}" alt="harusnya hp-7" width="200px" style="margin-bottom: 15%;">
            </div>
        </div>
    </div>
</section>
<!-- jemput sekarang mobile end -->

<div class="container d-none d-md-block" style="margin-top: 3%;">
    <div class="row">
        <div class="col-12">
            <hr/>
        </div>
    </div>
</div>
<section class="d-none d-md-block">
    <div class="container">
        <div class="row pt-4">
            <div class="col-2"></div>
            <div class="col-5 text-center">
                <p class="pb-3 font-weight-bold font32">Fitur Antar Sendiri</p>
                {{-- <p class="font20">Antar sendiri adalah fitur yang diperuntukkan bagi yang ingin <br>menyetor sampah sendiri.</p> --}}
            </div>
            <div class="col-5"></div>
        </div>
        <div class="row">
            <div class="col-3"></div>
            <div class="col-7">
                <p class="font20">Antar sendiri adalah fitur yang diperuntukkan bagi yang ingin <br>menyetor sampah sendiri.</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row py-5">
            <div class="col-2"></div>
            <div class="col-5 text-center">
                <img src="{{ asset('images/landing/number-black-1.png') }}" alt="harusnya number-black-1" width="40px">
                <p class="pt-5-c font-weight-bold font24">Fitur Antar Sendiri</p>
                <p class="font20">Fitur ini menyediakan pilihan <br>penyetoran sampah Anda sendiri</p>
            </div>
            <div class="col-5"></div>
        </div>
    </div>
</section>

<section class="mt-5 d-none d-md-block" style="background-color: #3dcc9c;">
    <div class="container">
        <div class="row mt-3">
            <div class="col-2"></div>
            <div class="col-5">
            </div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-8.png') }}" alt="harusnya hp-8" width="250px" style="margin-top: -126%;">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-9.png') }}" alt="harusnya hp-9" width="250px">
            </div>
            <div class="col-5 text-center text-white">
                <img src="{{ asset('images/landing/number-white-2.png') }}" alt="harusnya number-white-2" width="40px">
                <p class="pt-5-c font-weight-bold font24">Pilih fasilitas pengolahan sampah</p>
                <p class="font20">Sistem akan memberikan lokasi fasilitas <br>pengolahan sampah terdekat yang dapat <br>Anda kunjungi.</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mt-5 pt-5">
            <div class="col-2"></div>
            <div class="col-5">
                <div class="text-center">
                    <img src="{{ asset('images/landing/number-white-3.png') }}" alt="harusnya number-white-3" width="40px">
                </div>
                <div class="text-center text-white">
                    <p class="pt-5-c font-weight-bold font24">Cek jadwal operasional</p>
                    <p class="font20">Cek jadwal operasional dan ketentuan <br>yang ada pada fasilitas pengolahan <br>sampah yang ingin Anda kunjungi.</p>
                </div>
            </div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-10.png') }}" alt="harusnya hp-10" width="250px">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row pt-5">
            <div class="col-2"></div>
            <div class="col-3">
                <img src="{{ asset('images/landing/hp-11.png') }}" alt="harusnya hp-11" width="250px" style="margin-bottom: -80%;">
            </div>
            <div class="col-5 text-center text-white">
                <img src="{{ asset('images/landing/number-white-4.png') }}" alt="harusnya number-white-4" width="40px">
                <p class="pt-5-c font-weight-bold font24">Scan kode QR</p>
                <p class="font20">Admin fasilitas pengolahan sampah akan <br>melakukan scan kode QR Anda sebelum <br>menerima sampah yang Anda setor.</p>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
</section>
<!-- antar sendiri mobile start -->
<section class="d-block d-md-none" style= "background-color: #3dcc9c;padding-top: 10%;">
    <div class="container">
        <div class="row py-3 text-center">
            <div class="col-12">
                <p class="pt-2 font-weight-bold text-white subfont">Fitur Antar Sendiri</p>
                <p class="text-white">Antar sendiri adalah fitur yang diperuntukkan bagi yang ingin menyetor sampah sendiri.</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-1-white-green.png') }}" alt="harusnya number-1-white-green" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Pilih fitur antar sendiri</p>
                    <p class="text-white">Fitur ini menyediakan pilihan penyetoran sampah Anda sendiri.</p>
                </div>
                <img src="{{ asset('images/landing/hp-8.png') }}" alt="harusnya hp-8" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-white-2.png') }}" alt="harusnya number-white-2" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Pilih fasilitas pengolahan sampah</p>
                    <p class="text-white">Sistem akan memberikan lokasi fasilitas pengolahan sampah terdekat yang dapat Anda kunjungi.</p>
                </div>
                <img src="{{ asset('images/landing/hp-9.png') }}" alt="harusnya hp-9" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-white-3.png') }}" alt="harusnya number-white-3" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Cek jadwal pengolahan sampah</p>
                    <p class="text-white">Cek jadwal operasional dan ketentuannya yang ada pada fasilitas pengolahan sampah yang ingin Anda kunjungi.</p>
                </div>
                <img src="{{ asset('images/landing/hp-10.png') }}" alt="harusnya hp-10" width="200px">
            </div>
        </div>
        <div class="row pt-5 text-center">
            <div class="col-12">
                <div>
                    <img src="{{ asset('images/landing/number-white-4.png') }}" alt="harusnya number-white-4" width="30px">
                    <p class="pt-2 font-weight-bold text-white subfont">Scan kode QR</p>
                    <p class="text-white">Admin fasilitas pengolahan sampah </p>
                </div>
                <img src="{{ asset('images/landing/hp-7.png') }}" alt="harusnya hp-7" width="200px" style="margin-bottom:-25%;">
            </div>
        </div>
    </div>
</section>
<!-- antar sendiri mobile end -->
<section class="mb-5 pb-5" style="background-color: #F9F9FC; padding-top: 25%;">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 d-none d-md-block">
                <div class="pb-4">
                    <span class="font-weight-bold " style="font-size: 48px;">Download Aplikasi Go 4.0 Waste</span>
                </div>
                <div>
                    {{-- <a href="#"><img src="{{ asset('images/landing/apple-badge.png') }}"></a> --}}
                    <a href="#"><img src="{{ asset('images/landing/google-badge.png') }}"></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('styles')
<style>

        .font24{
            font-size:24px;
        }
        .font32{
            font-size:32px;
        }
        .font48{
            font-size:48px;
        }

        .font-custom-open-sans-regular{
            font-family: 'open-sans-regular', sans-serif !important;
            -webkit-font-smoothing: subpixel-antialiased !important;
        }

        .font-custom-open-sans-bold{
            font-family: 'open-sans-bold', sans-serif !important;
            -webkit-font-smoothing: subpixel-antialiased !important;
        }
        @font-face {
            font-family: 'open-sans-regular';
            src: url('../../fonts/opensans.eot');
            src: url('../../fonts/opensans.eot?#iefix') format('embedded-opentype'),
                url('../../fonts/opensans.woff2') format('woff2'),
                url('../../fonts/opensans.woff') format('woff'),
                url('../../fonts/opensans.svg#opensans') format('svg');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'open-sans-bold';
            src: url('../../fonts/opensans-bold.eot');
            src: url('../../fonts/opensans-bold.eot?#iefix') format('embedded-opentype'),
                url('../../fonts/opensans-bold.woff2') format('woff2'),
                url('../../fonts/opensans-bold.woff') format('woff'),
                url('../../fonts/opensans-bold.svg#opensans-bold') format('svg');
            font-weight: bold;
            font-style: normal;
        }

</style>
@endsection