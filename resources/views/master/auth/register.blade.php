@extends('master.auth.index')
@section('content')
    <div class="border p-4 rounded">
        <div class="text-center">
            <h3 class="">Sign Up</h3>
            <p>Already have an account? <a href="{{ url('') }}">Sign in here</a>
            </p>
        </div>
        <div class="login-separater text-center"> <span>SIGN UP WITH EMAIL</span>
            <hr />
        </div>
        <div class="form-body">
            <form class="row g-3" method="POST" action="{{ route('regist') }}">
                @csrf

                <div class="col-6">
                    <label for="username" class="form-label">username</label>
                    <input type="text" name="username"
                        class="form-control @error('username')
                        is-invalid
                    @enderror"
                        id="username" placeholder="jhon321" value="{{ old('username') }}">
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" name="phone"
                        class="form-control @error('phone')
                        is-invalid
                    @enderror"
                        id="phone" placeholder="jhon321" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="inputEmailAddress" class="form-label">Email Address</label>
                    <input type="email" name="email"
                        class="form-control @error('email')
                        is-invalid
                    @enderror"
                        id="inputEmailAddress" placeholder="example@user.com" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="user_ref" class="form-label">Refferal</label>
                    <input type="text" name="user_ref"
                        class="form-control @error('user_ref')
                        is-invalid
                    @enderror"
                        id="user_ref" placeholder="jhon321" value="{{ old('user_ref') }}">
                    @error('user_ref')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-6">
                    <label for="inputChoosePassword" class="form-label">Password</label>
                    <div class="input-group" id="show_hide_password">
                        <input type="password" name="password"
                            class="form-control border-end-0 @error('password')
                            is-invalid
                        @enderror"
                            id="inputChoosePassword" placeholder="Enter Password"> <a href="javascript:;"
                            class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <label for="inputChoosePassword" class="form-label">Retype Password</label>
                    <div class="input-group" id="show_hide_password">
                        <input type="password" name="password_confirmation"
                            class="form-control @error('password')
                            is-invalid
                        @enderror border-end-0"
                            id="inputChoosePassword"placeholder="Enter Password"> <a href="javascript:;"
                            class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                {{-- <div class="login-separater text-center"> <span>OPTIONAL</span>
                    <hr />
                </div> --}}

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" name="check" type="checkbox" id="flexSwitchCheckChecked" checked>
                        <label class="form-check-label" for="flexSwitchCheckChecked">I read and agree to Terms &
                            Conditions</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"><i class='bx bx-user'></i>Sign up</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
