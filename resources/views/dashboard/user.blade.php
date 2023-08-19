@extends('master.dashboard.index')
@push('script')
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            $.ajax({
                type: "GET",
                url: "{{ url('user-pie') }}",
                success: function(rs) {
                    var data = google.visualization.arrayToDataTable([
                        ['Task', 'Produk Ternak Harian'],
                        ['Telur', rs.data.telur],
                        ['Susu', rs.data.susu],
                        ['Daging', rs.data.daging],

                    ]);

                    var options = {
                        title: 'Presentasi Produk Ternak',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                    chart.draw(data, options);
                }
            })

        }
    </script>
@endpush
@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 justify-content-center">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Gems</p>
                            <h4 class="my-1 text-info">{{ nb($diamon) }}</h4>
                            {{-- <p class="mb-0 font-13">+2.5% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class='bx bxs-diamond'></i>
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
                            <i class='bx bxs-cookie'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Vaksin</p>
                            <h4 class="my-1 text-success">{{ $vaksin }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class='bx bx-test-tube'></i>
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
                            <p class="mb-0 text-secondary">Total Tools</p>
                            <h4 class="my-1 text-warning">{{ $tools }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class='bx bxs-bar-chart-alt-2'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    {{-- <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div id="piechart" style="width: 500px; height: 300px;"></div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
