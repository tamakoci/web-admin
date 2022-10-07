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
                            <th>Diamon</th>
                            <th>Equal to</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $t->diamon }}</td>
                                <td>{{ '=' }}</td>
                                <td>{{ $t->price }}</td>
                                <td>
                                    @if ($t->status == 1)
                                        <span class="badge bg-gradient-quepal text-white shadow-sm w-100">Active</span>
                                    @else
                                        <span class="badge bg-gradient-bloody text-white shadow-sm w-100">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ url('topup-diamon/' . $t->id) }}" method="POST">
                                        <button type="button" data-id="{{ $t->id }}"
                                            data-diamon="{{ $t->diamon }}" data-price="{{ $t->price }}"
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
                                <label for="customerno" class="col-sm-3 col-form-label">Diamon</label>
                                <div class="col-sm-9">
                                    <input type="number"
                                        class="form-control @error('diamon')
                                        is-invalid
                                    @enderror"
                                        id="diamon" name="diamon" placeholder="Enter Diamon"
                                        value="{{ old('diamon') }}">
                                    @error('diamon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Price</label>
                                <div class="col-sm-9">
                                    <input type="number"
                                        class="form-control @error('price')
                                        is-invalid
                                    @enderror"
                                        id="price" name="price" placeholder="Enter Price"
                                        value="{{ old('price') }}">
                                    @error('price')
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
                            @method('put')
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Diamon</label>
                                <div class="col-sm-9">
                                    <input type="number"
                                        class="form-control diamon @error('diamon')
                                        is-invalid
                                    @enderror"
                                        id="diamon" name="diamon" placeholder="Enter Diamon"
                                        value="{{ old('diamon') }}">
                                    @error('diamon')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Price</label>
                                <div class="col-sm-9">
                                    <input type="number"
                                        class="form-control price @error('price')
                                        is-invalid
                                    @enderror"
                                        id="price" name="price" placeholder="Enter Price"
                                        value="{{ old('price') }}">
                                    @error('price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-3 col-form-label status">Status</label>
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
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('.editBtn').on('click', function(e) {
                const id = $(this).data('id'),
                    diamon = $(this).data('diamon'),
                    price = $(this).data('price'),
                    status = $(this).data('status');
                $('#edtModal').modal('show');
                $('#formEdt').attr('action', "{{ url('/topup-diamon') }}" + "/" + id)
                $('.diamon').val(diamon)
                $('.price').val(price)
                $('.status').val(status)
            })
        })
    </script>
@endpush
