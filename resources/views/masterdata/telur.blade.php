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
                            <th>Date</th>
                            <th class="text-end">Harga /butir</th>
                            <th class="text-end">Presentase</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ dt($t->date) }}</td>
                                <td class="text-end">{{ nb($t->harga) }} IDR</td>
                                <td class="text-end">
                                    @if ($t->percent > 0)
                                        <h6 class="text-success">+{{ $t->percent }} %</h6>
                                    @elseif($t->percent == 0)
                                        <h6 class="text-secondary">{{ $t->percent }} %</h6>
                                    @else
                                        <h6 class="text-danger">{{ $t->percent }} %</h6>
                                    @endif

                                </td>
                                {{-- <td>{!! $t->percent > 0
                                    ? '<span class=" text-success">+' . $t->percent . '</span>'
                                    : '<span class=" text-danger">' . $t->percent . '</span>' !!}</td> --}}
                                <td class="text-center">
                                    <form action="{{ url('admin/telur/' . $t->id) }}" method="POST">
                                        <button type="button" data-id="{{ $t->id }}"
                                            data-harga="{{ $t->harga }}" data-date="{{ $t->date }}"
                                            data-percet="{{ $t->percet }}"
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
                                <label for="customerno" class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <input type="date"
                                        class="form-control @error('date')
                                        is-invalid
                                    @enderror"
                                        id="date" name="date" placeholder="Tanggal" value="{{ old('date') }}">
                                    @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Harga Sebelumnya</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control Lastharga" id="harga"
                                        placeholder="Harga Dulu" readonly>
                                    @error('harga')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Harga</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control hargaTerbaru @error('harga')
                                        is-invalid
                                    @enderror"
                                        id="harga" name="harga" placeholder="Enter Harga" value="{{ old('harga') }}"
                                        autofocus>
                                    @error('harga')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Presentase</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control presentase @error('percent')
                                        is-invalid
                                    @enderror "
                                        id="percent" name="percent" placeholder="000" value="{{ old('percent') }}"
                                        readonly>
                                    @error('percent')
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
                                <label for="customerno" class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <input type="date"
                                        class="form-control date @error('date')
                                        is-invalid
                                    @enderror"
                                        id="date" name="date" placeholder="Tanggal"
                                        value="{{ old('date') }}">
                                    @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Harga</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control hargaTerbaru harga @error('harga')
                                        is-invalid
                                    @enderror"
                                        id="harga" name="harga" placeholder="Enter Harga"
                                        value="{{ old('harga') }}" autofocus>
                                    @error('harga')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="customerno" class="col-sm-3 col-form-label">Presentase</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                        class="form-control percent presentase @error('percent')
                                        is-invalid
                                    @enderror "
                                        id="percent" name="percent" placeholder="000" value="{{ old('percent') }}"
                                        readonly>
                                    @error('percent')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
            LasthargaTelur();

            async function LasthargaTelur() {
                try {
                    const harga = await lastTelur();
                    $('.Lastharga').val(harga);
                    // Continue with the logic that uses the harga value
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            $('.editBtn').on('click', function() {
                const id = $(this).data('id'),
                    date = $(this).data('date'),
                    harga = $(this).data('harga'),
                    percent = $(this).data('percent')
                status = $(this).data('status')

                $('#edtModal').modal('show');
                $('#formEdt').attr('action', "{{ url('admin/telur') }}" + "/" + id)
                $('.date').val(date);
                $('.harga').val(harga);
                $('.percent').val(percent);
            })

            $('.hargaTerbaru').on('keyup', delay(function(e) {
                var hargaToday = $(this).val();

                lastTelur().then(function(harga) {
                    var percent = ((hargaToday - harga) / harga) * 100;
                    $('.presentase').val(percent.toFixed(2))
                    console.log('percenase', percent);

                }).catch(function(error) {
                    console.error('Error:', error);
                });
            }, 500));

            function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this,
                        args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }

            function lastTelur() {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        url: "{{ url('last-telur') }}", // Replace with the actual API endpoint
                        method: 'GET',
                        dataType: 'json', // Expected data type
                        success: function(rs) {
                            resolve(rs.data.harga);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            reject(errorThrown);
                        }
                    });
                });
            }

        })
    </script>
@endpush
