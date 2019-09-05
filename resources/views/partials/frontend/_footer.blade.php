<footer>
    <section class="d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-4">
                    <img src="{{ asset('images/landing/logo-footer.png') }}" alt="harusnya logo-footer" width="150px">
                </div>
                <div class="col-2"></div>
                <div class="col-6">
                    <div class="mb-3">
                        <span  style="font-size: 16px;">Untuk pertanyaan lebih lanjut silakan menghubungi kami</span>
                    </div>
                    <h4 style ="color: #3dcc9c; font-weight: bold;">Support@dws-solusi.net</h4>
                    <br/>
                    <a href="{{ route('syarat') }}" style="color: #3dcc9c;">Terms and Condition</a><br/>
                    <a href="{{ route('privasi') }}" style="color: #3dcc9c;">Privacy Policy</a>
                </div>

            </div>
            <div class="row my-5">
                <div class="col-12">
                    <span style="font-size: 12px">2019 Go 4.0 Waste - PT Solusi Digital Limbahan</span>
                </div>
            </div>
        </div>
    </section>

    <!-- footer mobile start -->
    <section class="d-block d-md-none" style="margin-top:15%;">
        <div class="container">
            <div class="row">
                <div class="col-12 pb-4 text-center">
                    <p class="font-weight-bold" style="font-size: 30px;">Fitur pengguna aplikasi<br/>
                        Go 4.0 Waste</p>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <div class="text-center">
                        {{--     --}}
                        <a href="#"><img src="{{ asset('images/landing/google-badge.png') }}"></a>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <span>Powered by</span><br/><br/>
                    <img src="{{ asset('images/landing/logo-footer.png') }}" alt="harusnya logo-footer" width="150px">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <p style="font-size: 18px;">Untuk pertanyaan lebih lanjut silakan <br/>menghubungi kami</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <p style="color: #3dcc9c; font-weight: bold; font-size: 23px;">Support@dws-solusi.net</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('syarat') }}" style="color: #3dcc9c;">Terms and Condition</a><br/>
                    <a href="{{ route('privasi') }}" style="color: #3dcc9c;">Privacy Policy</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p style="font-size: 11px;">2019 Go 4.0 Waste - PT Solusi Digital Limbahan</p>
                </div>
            </div>
        </div>

    </section>
    <!-- footer mobile end -->
</footer>
