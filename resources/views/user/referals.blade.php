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
        {{-- <div class="ms-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i
                        class="bx bx-plus"></i> Add</button>
            </div>
        </div> --}}
    </div>
    <!--end breadcrumb-->
    @if ($errors->any())
        <div class="error-post" data-msg="Invalid Input!"></div>
    @endif
    <div class="card radius-10">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="example">
                    <thead class="table-light">
                        <tr>
                            <th>Level</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $t->level }}</td>
                                <td>
                                    <div class="user-box dropdown">
                                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="{{ $t->user->getAvatar() }}" class="user-img" alt="user avatar">
                                            <div class="user-info ps-3">
                                                <p class="user-name mb-0">{{ strtoupper($t->user->username) }}</p>
                                                <p class="designattion mb-0">{!! $t->user->phone !!}</p>
                                                <p class="designattion mb-0">Join at : {!! date('d/m/Y', strtotime($t->user->created_at)) !!}</p>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('user-profile/' . $t->user->id) }}"><i
                                                        class="bx bx-user"></i><span>See
                                                        Profile</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
