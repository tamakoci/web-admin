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
                                    <p class="text-muted font-size-sm">{!! $user->getReferal() !!}</p>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <ul class="list-group list-group-flush">
                                @foreach ($wallet as $item => $val)
                                    @if ($item != 'hasil_ternak')
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                            <h6 class="mb-0"></i>
                                                {{ $item != 'hasil_ternak' ? ucfirst($item) : '' }}</h6>
                                            <span class="text-secondary">{{ $item != 'hasil_ternak' ? $val : '' }}</span>
                                        </li>
                                    @else
                                        @foreach ($val as $item => $val)
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <h6 class="mb-0"></i>
                                                    {{ ucfirst($val['name']) }}</h6>
                                                <span class="text-secondary">{{ $val['qty'] }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ url('user-profile') . '/' . $user->id }}" method="POST"
                                enctype="multipart/form-data">
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
                                        @if ($user->user_ref != null)
                                            <input type="text" name="user_ref" class="form-control" disabled
                                                value="{{ $user->user_ref }}" />
                                        @else
                                            <a href="{{ url('generate-referal') }}"
                                                class="btn btn-warning px-4">Generate</a>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0">Avatar</h6>
                                    </div>
                                    <div class="col-sm-9">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="preview-zone hidden">
                                                        <div class="box box-solid">
                                                            <div class="box-header with-border">
                                                                <div class="box-tools pull-right">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-xs remove-preview mb-2">
                                                                        <i class="bx bx-x e-2"></i> Reset Image
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="box-body">
                                                                <img src="{{ $user->getAvatar() }}" alt="preview image"
                                                                    width="200" class="updatepreview">
                                                                <p class="updatepreview">
                                                                    {{ $user->avatar == null ? 'default.png' : $user->username . '.png' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row" id="alert-info">
                                                        <div class="col-sm-1 mr-n3"><i
                                                                class="fas fa-exclamation-triangle text-danger alert-icon d-none"></i>
                                                        </div>
                                                        <div class="col-sm-11">
                                                            <span class="text-center text-danger ml-n5" id="alert">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="dropzone-wrapper d-none" id="dropz">
                                                        <div class="dropzone-desc">
                                                            <i class="glyphicon glyphicon-download-alt"></i>
                                                            <p>Klik disini untuk memilih file gambar, atau drag file disini
                                                            </p>
                                                        </div>
                                                        <input type="file" name="image" class="dropzone">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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
