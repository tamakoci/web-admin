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
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    Referal Code:
                </div>
                <div class="col-md-8">
                    <input type="text" name="" id="" class="form-control"
                        value="{{ auth()->user()->user_ref }}">
                </div>
            </div>
        </div>
    </div>
    <div class="card radius-10">
        <div class="card-body">
            @if (0 < count($referrals))
                <div class="d-flex align-items-start">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="nav flex-column nav-pills me-3 " id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                @foreach ($referrals as $key => $referral)
                                    <button class="nav-link @if ($key == 1) active @endif"
                                        id="tabs-level-{{ $key }}" data-bs-toggle="pill"
                                        data-bs-target="#v-tabs-level-{{ $key }}" type="button" role="tab"
                                        aria-controls="v-tabs-level-{{ $key }}" aria-selected="true">Level
                                        {{ $key }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach ($referrals as $key => $referral)
                                    <div class="tab-pane fade show @if ($key == 1) active @endif"
                                        id="v-tabs-level-{{ $key }}" role="tabpanel"
                                        aria-labelledby="v-tabs-level-{{ $key }}-tab">
                                        @if (0 < count($referral))
                                            <div class="">
                                                <table class="table mb-0">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Avatar</th>
                                                        <th>Username</th>
                                                        <th>Phone</th>
                                                        <th>Join date</th>
                                                    </tr>
                                                    <tbody>
                                                        @php
                                                            $no = 1;
                                                        @endphp
                                                        @foreach ($referral as $t)
                                                            <tr>
                                                                <td>{{ $no++ }}</td>
                                                                <td>
                                                                    <div class="user-box dropdown">
                                                                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                                                                            href="#" role="button"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <img src="{{ $t->user->getAvatar() }}"
                                                                                class="user-img" alt="user avatar">

                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <li>
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user-profile/' . $t->user->id) }}"><i
                                                                                        class="bx bx-user"></i><span>See
                                                                                        Profile</span></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="user-box dropdown">
                                                                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                                                                            href="#" role="button"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <div class="user-info ps-3">
                                                                                <p class="user-name mb-0">
                                                                                    {{ strtoupper($t->user->username) }}
                                                                                </p>
                                                                            </div>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <li>
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user-profile/' . $t->user->id) }}"><i
                                                                                        class="bx bx-user"></i><span>See
                                                                                        Profile</span></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="user-box dropdown">
                                                                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                                                                            href="#" role="button"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">

                                                                            <div class="user-info ps-3">
                                                                                <p class="user-name mb-0">
                                                                                    {{ strtoupper($t->user->phone) }}
                                                                                </p>
                                                                            </div>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <li>
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user-profile/' . $t->user->id) }}"><i
                                                                                        class="bx bx-user"></i><span>See
                                                                                        Profile</span></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="user-box dropdown">
                                                                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                                                                            href="#" role="button"
                                                                            data-bs-toggle="dropdown" aria-expanded="false">

                                                                            <div class="user-info ps-3">
                                                                                <p class="user-name mb-0">
                                                                                <p class="designattion mb-0">
                                                                                    {!! date('M d Y', strtotime($t->user->created_at)) !!}</p>
                                                                            </div>
                                                                        </a>
                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                            <li>
                                                                                <a class="dropdown-item"
                                                                                    href="{{ url('user-profile/' . $t->user->id) }}"><i
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
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            @endif


        </div>
    </div>

@endsection
