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

    <!--end breadcrumb-->
    <div class="card radius-10">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0" id="example">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>

                            <th>Username</th>
                            <th>Amount</th>
                            <th>Charge</th>
                            <th class="text-end">Final Amount</th>

                            @if ($status != 1)
                                <th>Information</th>
                            @endif
                            <th>Status</th>
                            @if ($status == 1)
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($table as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $t->username }}</td>
                                <td>{{ nb($t->amount) }}</td>
                                <td class="text-danger">-{{ $t->charge }}</td>
                                <td class="text-success text-end">{{ nb($t->final_amount) }}</td>

                                @if ($status != 1)
                                    <td>
                                        {{ $t->withdraw_information }}
                                    </td>
                                @endif
                                <td>
                                    {{-- {!! $t->getStatus() !!} --}}
                                    {!! $t->getStatus() !!}
                                </td>
                                @if ($status == 1)
                                    <td class="text-end">
                                        <button class="btn btn-info btnDetails" data-bs-toggle="modal"
                                            data-id="{{ $t->id }}" data-user_id="{{ $t->user_id }}"
                                            data-total="{{ $t->final_amount }}"
                                            data-bs-target="#exampleModal">Details</button>
                                    </td>
                                @endif


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $title }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('admin/withdraw') }}" method="POST">
                    @csrf
                    <input type="hidden" name="wd_id" id="id">
                    <div class="modal-body">
                        <div class="input-group">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">Nama Bank</th>

                                        <td class="text-secondary text-end" id="bank_name">Nama Bank</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Nama Akun:</th>

                                        <td class="text-secondary text-end" id="account_name">Nama Akun</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">No. Rekening</th>

                                        <td class="text-secondary text-end" id="account_no">00000000</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Total Withdraw</th>
                                        <td class="text-primary text-end" id="total_withdraw" id="total_wd">000000000</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mb-3 mt-2">
                                <label for="exampleInputPassword1" class="form-label h6">Withdraw Information:</label>
                                <textarea name="withdraw_information" id="" cols="60" rows="3" class="form-control"></textarea>
                            </div>


                            <div class="input-group">
                                <input type="submit" class="form-control btn btn-warning" name="type" value="Reject"
                                    aria-describedby="basic-addon1">
                                <input type="submit" class="form-control btn btn-primary" name="type" value="Approve"
                                    aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('.btnDetails').on('click', function(e) {
            var user_id = $(this).data('user_id')
            var id = $(this).data('id')
            var total = $(this).data('total')
            $.ajax({
                url: "{{ url('admin/cek-bank') }}",
                data: {
                    user_id: user_id
                },
                success: function(rs) {

                    var nama_bank = rs.data.nama_bank;
                    var account_name = rs.data.account_name;
                    var account_number = rs.data.account_number;
                    $('#bank_name').html(nama_bank);
                    $('#account_name').html(account_name);
                    $('#account_no').html(account_number);
                    $('#total_withdraw').html('IDR ' + formatNumber(total))
                    $('#id').val(id)
                }
            });
        });

        function formatNumber(number) {
            return parseFloat(number).toLocaleString('id-ID', {
                minimumFractionDigits: 0
            });
        }
    </script>
@endpush
