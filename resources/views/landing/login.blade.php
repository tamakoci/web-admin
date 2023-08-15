@extends('master.landing.index')
@section('content')
    <section class="error-area bg_cover"
        style="background-image:linear-gradient(to left, rgba(43, 53, 109, 0.226), rgb(49, 23, 104)), url({{ asset('') }}/land/img/dagan-ayam.png);">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-8">
                    <div class="login-box">
                        <div class="login-title text-center">
                            <img src="{{ asset('ayamku.png') }}" alt="logo" style="max-width: 300px">
                        </div>
                        <div class="login-input">
                            <form action="{{ url('login-post') }}" method="POST">
                                @csrf
                                <div class="input-box mt-10">
                                    <input type="text"
                                        class=" @error('username')
                                        id-invalid
                                    @enderror"
                                        id="username" name="username" placeholder="Username or email"
                                        value="{{ old('username') }}">
                                </div>
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="input-box mt-10">
                                    <input type="password" name="password"
                                        class=" border-end-0 @error('password')
                                        is-invalid
                                    @enderror"
                                        id="inputChoosePassword" placeholder="Enter Password">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="input-btn mt-10">
                                    <button class="main-btn" type="submit">login <i
                                            class="fal fa-arrow-right"></i></button>
                                    <span>Belum Punya Akun? <a href="{{ url('register') }}">Register</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
