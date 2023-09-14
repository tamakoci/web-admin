@extends('master.dashboard.index')

@section('content')
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 justify-content-center">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Member</p>
                            <h4 class="my-1 text-danger">{{ $member }}</h4>
                            {{-- <p class="mb-0 font-13">+8.4% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class='bx
                                bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total GEMS All</p>
                            <h4 class="my-1 text-info">{{ $gems }}</h4>
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
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Telur</p>
                            <h4 class="my-1 text-warning">{{ $telur }}</h4>
                            {{-- <p class="mb-0 font-13">+5.4% from last week</p> --}}
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                            <i class='bx bxs-circle'></i>
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
                            <p class="mb-0 text-secondary">Pakan</p>
                            <h4 class="my-1 text-success">{{ $pakan }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                            <i class='bx bxs-cookie'></i>
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
                            <p class="mb-0 text-secondary">Vaksin</p>
                            <h4 class="my-1 text-danger">{{ $vaksin }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                            <i class='bx bxs-first-aid'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Tools</p>
                            <h4 class="my-1 text-info">{{ $tools }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                            <i class='bx bxs-wrench'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div id="chart"></div>
                    {{-- <div id="piechart" style="width: 500px; height: 300px;"></div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    {{-- <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            $.ajax({
                type: "GET",
                url: "{{ url('chart') }}",
                success: function(rs) {
                    var data = google.visualization.arrayToDataTable([
                        ['Task', 'Produk Ternak Harian'],
                        ['Telur', rs.data.telur],
                        ['Susu', rs.data.susu],
                        ['Daging', rs.data.daging],

                    ]);

                    var options = {
                        title: 'Presentasi Produk Ternak User',
                        is3D: true,
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                    chart.draw(data, options);
                }
            })

        }
    </script> --}}

    <script type="text/javascript">
        var options = {
            series: [{
                name: "Harga Telur",
                data: @json(hargaPasar()['price'])
            }],
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },

            title: {
                text: 'Harga Telur Harian',
                align: 'left'
            },
            subtitle: {
                text: "{{ hargaPasar()['now'] }}",
                align: 'left'
            },
            labels: @json(hargaPasar()['date']),
            xaxis: {
                type: 'datetime',
            },
            yaxis: {
                opposite: true
            },
            legend: {
                horizontalAlign: 'left'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush
