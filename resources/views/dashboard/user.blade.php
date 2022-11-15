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
    </div>
    <div class="row row-cols-1 row-cols-md-1 row-cols-xl-1 justify-content-center">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success d-flex">
                <div class="card-body justify-content-center">
                    <div id="piechart" style="width: 900px; height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
