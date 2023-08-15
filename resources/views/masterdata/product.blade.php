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
                <table class="table align-middle mb-0" id="example">
                    <thead class="table-light">
                        <tr>
                            <td>No</td>
                            <th>Name</th>
                            <th>Satuan</th>
                            <th>Setara Diamon</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $t->name }}</td>
                                <td>{{ $t->satuan }}</td>
                                <td>{{ $t->dm }}</td>
                                <td>
                                    @if ($t->status == 1)
                                        <span class="badge bg-gradient-quepal text-white shadow-sm w-100">Active</span>
                                    @else
                                        <span class="badge bg-gradient-bloody text-white shadow-sm w-100">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ url('admin/product/' . $t->id) }}" method="POST">
                                        <button type="button" data-id="{{ $t->id }}" data-name="{{ $t->name }}"
                                            data-satuan="{{ $t->satuan }}" data-dm="{{ $t->dm }}"
                                            data-status="{{ $t->status }}"
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
                        <form action="" method="POST" id="formAdd">
                            @csrf
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('name')
                                        is-invalid
                                    @enderror"
                                        id="name" name="name" placeholder="Enter Product Name"
                                        value="{{ old('customer') }}">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Satuan</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('satuan')
                                        is-invalid
                                    @enderror"
                                        id="satuan" name="satuan" placeholder="Enter Satuan Produk"
                                        value="{{ old('satuan') }}">
                                    @error('satuan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Setara Diamon</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('dm')
                                        is-invalid
                                    @enderror "
                                        id="dm" name="dm" placeholder="Enter Diamon"
                                        value="{{ old('dm') }}">
                                    @error('dm')
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
                        <h5 class="mb-0 text-info">Add {{ $title }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="border p-4 rounded">
                        {{-- <hr /> --}}
                        <form action="" method="POST" id="formEdt">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('name')
                                        is-invalid
                                    @enderror name"
                                        id="name" name="name" placeholder="Enter Product Name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Satuan</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('satuan')
                                        is-invalid
                                    @enderror satuan"
                                        id="satuan" name="satuan" placeholder="Enter Satuan Produk"
                                        value="{{ old('satuan') }}">
                                    @error('satuan')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Setara Diamon</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control @error('dm')
                                        is-invalid
                                    @enderror dm"
                                        id="dm" name="dm" placeholder="Enter Diamon"
                                        value="{{ old('dm') }}">
                                    @error('dm')
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
        $(document).ready(function() {
            $('.editBtn').on('click', function() {
                const id = $(this).data('id'),
                    name = $(this).data('name'),
                    satuan = $(this).data('satuan'),
                    dm = $(this).data('dm')
                status = $(this).data('status')

                $('#edtModal').modal('show');
                $('#formEdt').attr('action', "{{ url('admin/product') }}" + "/" + id)
                $('.name').val(name);
                $('.satuan').val(satuan);
                $('.dm').val(dm);
                $('.status').val(status);

            })
        })
    </script>
@endpush
