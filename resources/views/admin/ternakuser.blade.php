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

    <div class="card radius-10">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="example">
                    <thead class="table-light">
                        <tr>
                            <td>No</td>
                            <th>Username</th>
                            <th>Jumlah Gems</th>
                            <th>Jumlah Ternak</th>
                            <th>Nama Bank</th>
                            <th>Nama Akun</th>
                            <th>No Rekening</th>
                            <th>Tanggal Beli</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $t->username }}</td>
                                <td>{{ nb($t->gems) . ' GEMS' }}</td>
                                <td>{{ $t->masterplan_count . ' Ayam' }}</td>
                                <td>{{ $t->user_bank->nama_bank ?? 'BANK DUMMY' }}</td>
                                <td>{{ $t->user_bank->account_name ?? 'DUMMY' }}</td>
                                <td>{{ $t->user_bank->account_number ?? '0000' }}</td>
                                <td>{{ dt($t->created_at) }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.editBtn').on('click', function(e) {
                const id = $(this).data('id'),
                    bank = $(this).data('bank'),
                    code = $(this).data('code'),
                    status = $(this).data('status');
                $('#edtModal').modal('show');
                $('#formEdt').attr('action', "{{ url('admin/bank') }}" + "/" + id)
                $('.bank').val(bank)
                $('.bank-code').val(code)
                // console.log(code);
                $('.status').val(status)
            })
        })
    </script>
@endpush
