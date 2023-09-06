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
        <div class="ms-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal"><i
                        class="bx bx-plus"></i> Add</button>
            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card radius-10">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table  mb-0" id="example">
                    <thead class="table-light">
                        <tr>
                            <td>No</td>
                            <th>User</th>
                            <th>Title</th>
                            <th>Message</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{!! $t->user_id != null
                                    ? '<span class="badge bg-gradient-bloody text-white shadow-sm w-100">' . $t->user->username . '</span>'
                                    : ' <span class="badge bg-gradient-quepal text-white shadow-sm w-100">All User</span>' !!}</td>
                                <td>{{ $t->title }}</td>
                                <td>{{ $t->message }}</td>

                                <td class="text-end">
                                    <form action="{{ url('admin/notif-delete/' . $t->id) }}" method="POST">
                                        <button type="button" data-id="{{ $t->id }}"
                                            data-title="{{ $t->title }}" data-message="{{ $t->message }}"
                                            data-user_id="{{ $t->user_id != null ? $t->user_id : '000' }}"
                                            class="btn btn-warning btn-sm editBtn">edit</button>
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">delete</button>
                                    </form>
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
                        <h5 class="mb-0 text-info">Add {{ $title }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="border p-4 rounded">
                        {{-- <hr /> --}}
                        <form action="{{ route('notif.post') }}" method="POST" id="formAdd">
                            @csrf
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('title')
                                        is-invalid
                                    @enderror"
                                        id="title" name="title" placeholder="Enter title"
                                        value="{{ old('title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Message</label>
                                <div class="col-sm-9">
                                    <textarea type="text"
                                        class="form-control @error('Message')
                                        is-invalid
                                    @enderror"
                                        id="message" name="message" placeholder="Enter message" value="{{ old('message') }}" rows="5"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">To User</label>
                                <div class="col-sm-9">
                                    <select name="user_id" id="" class="form-select form-control">
                                        <option value="000" selected>ALL USER</option>
                                        @foreach ($user as $item)
                                            <option value="{{ $item->id }}">{{ $item->username }}</option>
                                        @endforeach
                                    </select>
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
    <div class="modal fade" id="edtModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    {{-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> --}}
                    <div class="card-title d-flex align-items-center">
                        <div>
                            <i class="bx bx-add-to-queue me-1 font-22 text-info"></i>
                        </div>
                        <h5 class="mb-0 text-info">Update {{ $title }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="border p-4 rounded">
                        {{-- <hr /> --}}
                        <form action="" method="POST" id="formEdt">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Title</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control title @error('title')
                                        is-invalid
                                    @enderror"
                                        id="title" name="title" placeholder="Enter title"
                                        value="{{ old('title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Message</label>
                                <div class="col-sm-9">
                                    <textarea type="text"
                                        class="form-control message @error('Message')
                                        is-invalid
                                    @enderror"
                                        id="message" name="message" placeholder="Enter message" value="{{ old('message') }}" cols="30"
                                        rows="5"></textarea>

                                    @error('message')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">To User</label>
                                <div class="col-sm-9">
                                    <select name="user_id" id="" class="form-select user_id form-control">
                                        <option value="000" selected>ALL USER</option>
                                        @foreach ($user as $item)
                                            <option value="{{ $item->id }}">{{ $item->username }}</option>
                                        @endforeach
                                    </select>
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
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('.editBtn').on('click', function(e) {
                const id = $(this).data('id'),
                    title = $(this).data('title'),
                    message = $(this).data('message'),
                    user_id = $(this).data('user_id');
                // console.log(user_id);
                $('#edtModal').modal('show');
                $('#formEdt').attr('action', "{{ url('admin/notif-edit') }}" + "/" + id)
                $('.title').val(title)
                $('.message').val(message)
                $('.user_id').val(user_id)
            })
        })
    </script>
@endpush
