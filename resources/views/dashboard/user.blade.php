@extends('master.dashboard.index')
@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 justify-content-center">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Diamon</p>
                            <h4 class="my-1 text-info">{{ $diamon }}</h4>
                            {{-- <p class="mb-0 font-13">+2.5% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class='bx bxs-cart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Pakan</p>
                            <h4 class="my-1 text-danger">{{ $pakan }}</h4>
                            {{-- <p class="mb-0 font-13">+5.4% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Telur</p>
                            <h4 class="my-1 text-warning">{{ $telur }}</h4>
                            {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Susu</p>
                            <h4 class="my-1 text-warning">{{ $susu }}</h4>
                            {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Daging</p>
                            <h4 class="my-1 text-warning">{{ $daging }}</h4>
                            {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
