@extends('master.landing.index')
@section('content')
    <section class="error-area bg_cover"
        style="background-image:linear-gradient(to left, rgba(43, 53, 109, 0.226), rgb(49, 23, 104)), url({{ asset('') }}/land/img/dagan-ayam.png);">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-8">
                    <div class="login-box">
                        <div class="login-title text-center">
                            <img src="{{ asset('logo.png') }}" alt="logo" style="max-width: 300px">
                        </div>
                        <div class="login-input">
                            <form action="{{ route('regist') }}" method="POST">
                                @csrf
                                <div class="input-box mt-10">
                                    <input type="text"
                                        class=" @error('username')
                                        id-invalid
                                    @enderror"
                                        id="username" name="username" placeholder="Username" value="{{ old('username') }}">
                                </div>
                                @error('username')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="input-box mt-10">
                                    <input type="text"
                                        class=" @error('phone')
                                        id-invalid
                                    @enderror"
                                        id="phone" name="phone" placeholder="Phone" value="{{ old('phone') }}">
                                </div>
                                @error('phone')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="input-box mt-10">
                                    <input type="email"
                                        class=" @error('email')
                                        id-invalid
                                    @enderror"
                                        id="email" name="email" placeholder="Email Address"
                                        value="{{ old('email') }}">
                                </div>
                                @error('email')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div class="input-box mt-10">
                                    <input type="text"
                                        class=" @error('user_ref')
                                        id-invalid
                                    @enderror"
                                        id="user_ref" name="user_ref" placeholder="Referals" value="{{ old('user_ref') }}">
                                </div>
                                @error('email')
                                    <div class="text-danger">
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
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="input-box mt-10">
                                    <input type="password" name="password_confirmation"
                                        class=" border-end-0 @error('password_confirmation')
                                        is-invalid
                                    @enderror"
                                        id="inputChoosepassword_confirmation" placeholder="Confirm Password">
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="input-btn mt-10">
                                    <button class="main-btn" type="submit">submit <i
                                            class="fal fa-arrow-right"></i></button>
                                    <span>Sudah Punya Akun? <a href="{{ url('login') }}">Login</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
