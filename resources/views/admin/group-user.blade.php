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
                            <th>User</th>
                            <th>Sum Gems</th>
                            <th>Nama Bank</th>
                            <th>Nama Akun</th>
                            <th>No Rekening</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <td><img src="{{ $t->getAvatar() }}" alt="image{{ $t->username }}" width="100px"></td> --}}
                        @foreach ($table as $key => $t)
                            <?php $cek = checkBank($t->nama_bank, $t->account_name);
                            $gems[$key] = 0; ?>
                            {{-- @dd($t->nama_bank) --}}
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <ul class="list-group list-group-numbered">
                                        @foreach ($cek as $item)
                                            {{-- <li class="list-group-item">{{ $item->username }} ||
                                                {{ GemsUser($item->user_id) }}</li> --}}
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">{{ $item->username }}</div>
                                                    {{ nb(GemsUser($item->user_id)) }} GEMS
                                                </div>
                                                {{-- <span class="badge bg-primary rounded-pill">1
                                                    Gems</span> --}}
                                            </li>
                                            <?php $gems[$key] += GemsUser($item->user_id); ?>
                                        @endforeach
                                    </ul>

                                </td>
                                <td>{{ nb($gems[$key]) }} Gems</td>
                                <td>{{ $t->nama_bank }}</td>
                                <td>{{ $t->account_name }}</td>
                                <td>{{ $t->account_number }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
