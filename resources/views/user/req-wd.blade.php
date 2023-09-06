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
    <div class="container">
        <div class="main-body">
            <div class="row">
                <div class="col-md-4 col-lg-4 mb-8">
                    <div class="card b-radius--10 " style="min-height: 16rem;">
                        <div class="card-body text-center" style="display: table; min-height: 16rem; overflow: hidden;">
                            <div style="display: table-cell; vertical-align: middle;">
                                <h3>Available Balance</h3>
                                <h1 class="display-4 font-weight-bold">Rp {{ nb($diamon) }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 mb-4">
                    <div class="card card-deposit method-card b-radius--10" style="min-height: 16rem;">
                        <h5 class="card-header text-center">{{ 'Withdraw Gems to IDR' }}</h5>

                        <div class="card-body card-body-deposit">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">Limit:</th>

                                        <td class="text-success text-end">100.000 - 1.000.000 IDR</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Charge:</th>

                                        <td class="text-danger text-end">0 IDR + 0%</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Processing Time -</th>

                                        <td class="text-primary text-end">1 x 24 Hours</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-grid gap-2">
                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Withdraw Now</button>
                            </div>
                            {{-- <ul class="list-group list-group-flush">
                                <li class="list-group-item"><span>Limit :</span><span class="text-end text-success">1</span></li>
                                <li class="list-group-item">A second item</li>
                                <li class="list-group-item">A third item</li>
                                <li class="list-group-item">A fourth item</li>
                                <li class="list-group-item">And a fifth one</li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th scope="row">Available Balance:</th>

                                    <td class="text-info text-end">{{ nb($diamon) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Limit:</th>

                                    <td class="text-success text-end">100.000 - 1.000.000 IDR</td>
                                </tr>
                                <tr>
                                    <th scope="row">Charge:</th>

                                    <td class="text-danger text-end">0 IDR + 0%</td>
                                </tr>
                                <tr>
                                    <th scope="row">Processing Time -</th>

                                    <td class="text-primary text-end">1 x 24 Hours</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class=" ml-5 mr-5">Amount</h6>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="amount" id="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
