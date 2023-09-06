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
                            <th>No</th>
                            <th>Avatar</th>
                            <th>Username</th>
                            {{-- <th>Gems</th> --}}
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ $t->getAvatar() }}" alt="image{{ $t->username }}" width="100px"></td>
                                <td>{{ $t->username }}</td>
                                {{-- <td>{{ nb($t->wallet->diamon) }}</td> --}}
                                <td>{{ $t->email }}</td>
                                <td>{{ $t->phone }}</td>
                                {{-- <td>{!! $t->getRole() !!}</td> --}}
                                <td>
                                    {!! $t->getStatus() !!}
                                    {{-- @dd($t->getStatus()) --}}
                                </td>
                                <td class="text-end">
                                    {{-- <button type="button" data-id="{{ $t->id }}"
                                        data-username="{{ $t->username }}" data-avatar="{{ $t->getAvatar() }}"
                                        data-email="{{ $t->email }}" data-phone="{{ $t->phone }}"
                                        data-status="{{ $t->status }}" data-role="{{ $t->user_role }}"
                                        class="btn btn-warning btn-sm btnEdit">edit</button> --}}
                                    {{-- <form action="{{ url('admin/manage-user') . '/' . $t->id }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">delete</button>
                                    </form> --}}
                                    <a href="{{ url('user-profile/' . $t->id) }}" class="btn btn-warning btn-sm">detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> --}}
                    <div class="card-title d-flex align-items-center">
                        <div>
                            <i class="bx bx-add-to-queue me-1 font-22 text-info"></i>
                        </div>
                        <h5 class="mb-0 text-info">Add Users</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="border p-4 rounded">
                        {{-- <hr /> --}}
                        <form action="" method="POST" id="formAdd" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Customer No</label>
                                <div class="col-sm-9">
                                    <input type="number"
                                        class="form-control @error('customer')
                                        is-invalid
                                    @enderror"
                                        id="customerno" name="customer" placeholder="Enter Customer number"
                                        value="{{ old('customer') }}">
                                    @error('customer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="jumlah" class="col-sm-3 col-form-label">Jumlah</label>
                                <div class="col-sm-9">
                                    <input type="number"
                                        class="form-control @error('qty')
                                        is-invalid
                                    @enderror"
                                        id="qty" name="qty" placeholder="Jumlah" value="{{ old('qty') }}">
                                    @error('qty')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select name="status" id="status" class="form-select status">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputAddress4" class="col-sm-3 col-form-label">Avatar</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="preview-zone hidden">
                                                    <div class="box box-solid">
                                                        <div class="box-header with-border">
                                                            <div class="box-tools pull-right">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-xs remove-preview d-none mb-2">
                                                                    <i class="bx bx-x e-2"></i> Reset Image
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="box-body"></div>
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
                                                <div class="dropzone-wrapper" id="dropz">
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
                                    @error('image')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <input type="submit" class="btn btn-primary px-5">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> --}}
                    <div class="card-title d-flex align-items-center">
                        <div>
                            <i class="bx bx-add-to-queue me-1 font-22 text-info"></i>
                        </div>
                        <h5 class="mb-0 text-info">Update {{ 'User' }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="border p-4 rounded">
                        {{-- <hr /> --}}
                        <form action="" method="POST" id="formEdt" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control username" id="username" name="username"
                                        placeholder="Enter username">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="jumlah" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control email" id="email" name="email"
                                        placeholder="user@mail.com">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="jumlah" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control phone" id="phone" name="phone"
                                        placeholder="user phone">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="jumlah" class="col-sm-3 col-form-label">Role</label>
                                <div class="col-sm-9">
                                    <select name="role" id="role" class="form-select role">
                                        {{-- @dd(auth()->user()) --}}
                                        @foreach ($role as $r)
                                            <option value="{{ $r->id }}"
                                                @if (auth()->user()->user_role < $r->id) class="d-none" @endif>{{ $r->role_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    <select name="status" id="status" class="form-select status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputAddress4" class="col-sm-3 col-form-label">Avatar</label>
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
                                                        <div class="box-body"></div>
                                                    </div>
                                                </div>
                                                <img src="" alt="preview image" width="500"
                                                    class="updatepreview">

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
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <input type="submit" class="btn btn-primary px-5" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
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
                            '<img width="500" src="' + e.target.result + '" />' +

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


        $('.btnEdit').on('click', function(e) {
            var id = $(this).data("id"),
                ava = $(this).data("ava"),
                username = $(this).data("username"),
                email = $(this).data("email"),
                phone = $(this).data("phone"),
                role = $(this).data("role"),
                status = $(this).data("status"),
                avatar = $(this).data("avatar");
            $("#editModal").modal("show");
            $('.username').val(username)
            $('.email').val(email)
            $('.phone').val(phone)
            $('.role').val(role)
            $('.status').val(status)
            $('#formEdt').attr("action", "{{ url('admin/manage-user') }}" + '/' + id);
            $('.updatepreview').attr('src', `${avatar}`)
        })
    </script>
@endpush
