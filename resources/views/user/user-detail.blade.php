@extends('master.dashboard.index')
@push('style')
    <style>
        .dropzone-wrapper {
            border: 2px dashed #91b0b3;
            color: #92b0b3;
            position: relative;
            height: 150px;
        }

        .dropzone-desc {
            position: absolute;
            margin: 0 auto;
            left: 0;
            right: 0;
            text-align: center;
            width: 40%;
            top: 50px;
            font-size: 16px;
        }

        .dropzone,
        .dropzone:focus {
            position: absolute;
            outline: none !important;
            width: 100%;
            height: 150px;
            cursor: pointer;
            opacity: 0;
        }

        .dropzone-wrapper:hover,
        .dropzone-wrapper.dragover {
            background: #ecf0f5;
        }

        .preview-zone {
            text-align: center;
        }

        .preview-zone .box {
            box-shadow: none;
            border-radius: 0;
            margin-bottom: 0;
        }

        .remove-preview {
            padding: 1px 2px;
            font-size: 15px;
        }
    </style>
@endpush
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
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="{{ $user->getAvatar() }}" alt="profile-{{ $user->username }}"
                                    class="rounded-circle p-1 bg-primary" width="200">
                                <div class="mt-3">
                                    <h4>{{ strtoupper($user->username) }}</h4>
                                    <p class="text-secondary mb-1">{{ $user->email }}</p>
                                    <p class="text-muted font-size-sm">{!! $user->getRole() !!}</p>
                                </div>
                                <div class="mt-2">
                                    <table class="table table-berdered">
                                        <tbody>
                                            <tr>
                                                <td><i class='bx bxs-diamond'></i></td>
                                                <td>{{ $diamon }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <i class='bx bxs-circle'></i>
                                                </td>
                                                <td>{{ $telur }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class='bx bxs-cookie'></i>
                                                </td>
                                                <td>{{ $pakan }}</td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <i class='bx bxs-first-aid'></i>
                                                </td>
                                                <td>{{ $vaksin }}</td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <i class='bx bxs-wrench'></i>
                                                </td>
                                                <td>{{ $tools }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="#" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Username</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="username" class="form-control" disabled
                                            value="{{ $user->username }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Email</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="email" class="form-control"
                                            value="{{ $user->email }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Phone</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ $user->phone }}" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Referal</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" name="user_ref" class="form-control" disabled
                                            value="{{ $user->user_ref }}" />
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                    {{-- <div class="card radius-10">
                        <div class="card-header">
                            <h5> Referrals Tree {{ $user->username }}</h5>
                        </div>
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
                                                        data-bs-target="#v-tabs-level-{{ $key }}" type="button"
                                                        role="tab" aria-controls="v-tabs-level-{{ $key }}"
                                                        aria-selected="true">Level
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
                                                            <div class="table-responsive">
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
                                                                                            data-bs-toggle="dropdown"
                                                                                            aria-expanded="false">
                                                                                            <img src="{{ $t->user->getAvatar() }}"
                                                                                                class="user-img"
                                                                                                alt="user avatar">

                                                                                        </a>
                                                                                        <ul
                                                                                            class="dropdown-menu dropdown-menu-end">
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
                                                                                            data-bs-toggle="dropdown"
                                                                                            aria-expanded="false">
                                                                                            <div class="user-info ps-3">
                                                                                                <p class="user-name mb-0">
                                                                                                    {{ strtoupper($t->user->username) }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </a>
                                                                                        <ul
                                                                                            class="dropdown-menu dropdown-menu-end">
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
                                                                                            data-bs-toggle="dropdown"
                                                                                            aria-expanded="false">

                                                                                            <div class="user-info ps-3">
                                                                                                <p class="user-name mb-0">
                                                                                                    {{ strtoupper($t->user->phone) }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </a>
                                                                                        <ul
                                                                                            class="dropdown-menu dropdown-menu-end">
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
                                                                                            data-bs-toggle="dropdown"
                                                                                            aria-expanded="false">

                                                                                            <div class="user-info ps-3">
                                                                                                <p class="user-name mb-0">
                                                                                                <p
                                                                                                    class="designattion mb-0">
                                                                                                    {!! date('M d Y', strtotime($t->user->created_at)) !!}
                                                                                                </p>
                                                                                            </div>
                                                                                        </a>
                                                                                        <ul
                                                                                            class="dropdown-menu dropdown-menu-end">
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
                                                        @else
                                                            <div class="table-responsive nowrap">
                                                                <table class="table mb-0">
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Avatar</th>
                                                                        <th>Username</th>
                                                                        <th>Phone</th>
                                                                        <th>Join date</th>
                                                                    </tr>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th colspan="5" class="text-center">No
                                                                                Record</th>
                                                                        </tr>
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
                    </div> --}}
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            Transaction Log
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0" id="example">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>TRX Date</th>
                                            <th>Trasaction ID</th>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>Next Amount</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($table as $t)
                                            <tr class="{{ $t->trx_type == '+' ? 'text-success' : 'text-danger' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('h:i d-M', strtotime($t->created_at)) }}</td>
                                                <td>{{ $t->trx_id }}</td>
                                                <td><a href="#">{{ $t->user->username }}</a></td>
                                                <td class="">{{ $t->last_amount }}</td>
                                                <td>
                                                    {{ $t->final_amount }}
                                                </td>
                                                <td>{{ $t->detail }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    </div>
@endsection
@push('script')
    <script>
        function readFile(input) {
            var excel = "";
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                var fileExtension = ['jpg', 'jpeg', 'png', 'svg'];
                var ext = filenameExtention(input.files);
                if ($.inArray(ext, fileExtension) == -1) {
                    $('#alert').text('Only image formats are allowed');
                    $('.alert-icon').removeClass('d-none');
                } else {
                    $('#alert-info').addClass('d-none');
                    $('.buttons').removeClass('d-none');
                    reader.onload = function(e) {
                        var htmlPreview =
                            '<img width="200" src="' + e.target.result + '" />' +

                            '<p>' + input.files[0].name + '</p>';
                        var wrapperZone = $(input).parent();
                        var previewZone = $(input).parent().parent().find('.preview-zone');
                        var boxZone = $(input).parent().parent().find('.preview-zone').find('.box').find('.box-body');

                        wrapperZone.removeClass('dragover');
                        previewZone.removeClass('hidden');
                        boxZone.empty();
                        boxZone.append(htmlPreview);
                    };
                    $('.dropzone-wrapper').addClass('d-none');
                    $(".remove-preview").removeClass('d-none');
                    reader.readAsDataURL(input.files[0]);
                }
            }
        }



        function reset(e) {
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();
            $('.dropzone-wrapper').removeClass('d-none');
            $('.buttons').addClass('d-none');


        }

        function filenameExtention(file) {
            var fileName = file[0].name
            var extention = fileName.replace(/^.*\./, '');
            // var extention = fileName;
            return extention;
        }

        $(".dropzone").change(function() {
            readFile(this);
        });
        $('.dropzone-wrapper').on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });
        $('.dropzone-wrapper').on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });


        $('.remove-preview').on('click', function() {
            var boxZone = $(this).parents('.preview-zone').find('.box-body');
            var previewZone = $(this).parents('.preview-zone');
            var dropzone = $(this).parents('.form-group').find('.dropzone');
            boxZone.empty();
            previewZone.addClass('hidden');
            reset(dropzone);
            $(this).addClass('d-none')
            $('.dropzone-wrapper').removeClass('d-none')
            $('.updatepreview').addClass('d-none')
        });
    </script>
@endpush
