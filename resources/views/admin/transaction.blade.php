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
@endsection
