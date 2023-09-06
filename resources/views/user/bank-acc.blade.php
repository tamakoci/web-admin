@extends('master.dashboard.index')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">{{ $title }}</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-category"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    {{-- @dd($acc); --}}
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('user-profile') . '/' . $user->id }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">BANK ID</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="bank_id" class="form-control"
                                            value="{{ $acc['nama_bank'] }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Nama Akun</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="account_name" class="form-control"
                                            value="{{ $acc['nama_akun'] }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">No Rekening</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="account_number" class="form-control"
                                            value="{{ $acc['no_rek'] }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Kota Cabang</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="bank_city" class="form-control"
                                            value="{{ $acc['kota_cabang'] }}" />
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                                    </div>
                                </div> --}}
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
