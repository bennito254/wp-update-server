<?php

use App\Models\PackagesModel;

/** @var \App\Entities\PackageEntity[] $packages */
$packages = model(PackagesModel::class)->findAll();

?>
<div class="d-flex justify-content-between">
    <h1>Dashboard</h1>
    <div>
        <span id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </span>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Statistics</h4>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: scroll">
                            <table class="table mb-0">
                                <tbody>
                                <?php
                                foreach ($packages as $package) {
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="<?php echo $package->getIcons()['1x'] ?>" class="thumb-md align-self-center rounded-circle" alt="...">
                                                </div>
                                                <div class="flex-grow-1 ms-1 text-truncate">
                                                    <h6 class="my-0 fw-medium text-dark fs-14"><?php echo $package->title; ?></h6>
                                                    <p class="text-muted mb-0">Version <?php echo $package->getVersion(); ?>
                                                    </p>
                                                </div><!--end div-->
                                            </div> <!--end div-->
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table><!--end /table-->
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <canvas id="overviewLineChart" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <canvas id="doughnut" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
    <div class="">
        <div class="">
            <?php
            $softwares = (new \App\Libraries\Reports())->getSoftwares();
            ?>
            <div class="row text-center">
                <div class="col-md-6 col-sm-12">
                    <div class="card bg-gradient-purple">
                        <div class="card-body">
                            <h3>Packages Installations</h3>
                            <div class="row">
                                <div class="col-sm-5">
                                    <div>
                                        <canvas id="installedVersions" height="300"></canvas>
                                    </div>
                                    <?php
                                    $insVersions = [
                                        'label'     =>  [],
                                        'value'     =>  [],
                                    ];
                                    foreach ($softwares['installedVersion'] as $installedVersion) {
                                        $insVersions['label'][] = $installedVersion->installed_version;
                                        $insVersions['value'][] = (int)$installedVersion->count;
                                    }
                                    ?>
                                    <script>
                                        var installedVersionsLabels = <?php echo json_encode($insVersions['label']) ?>;
                                        var installedVersionsValues = <?php echo json_encode($insVersions['value']) ?>;
                                    </script>
                                </div>
                                <div class="col-sm-7">
                                    <div>
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                            <tr>
                                                <th>Package</th>
                                                <th>Installations</th>
                                            </tr>
                                            </thead>
                                            <?php
                                            foreach ($softwares['installedVersion'] as $installedVersion) {
                                                ?>
                                                <tr>
                                                    <th><a href="<?php echo route('admin.packages.view', $installedVersion->installed_version); ?>"><?php echo $installedVersion->installed_version ?></a></th>
                                                    <td><?php echo $installedVersion->count ?></td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card bg-gradient-orange">
                        <div class="card-body">
                            <h3>WordPress Versions</h3>
                            <div>
                                <canvas id="wpVersions" height="300"></canvas>
                            </div>
                            <?php
                            $wpVersions = [
                                'label'     =>  [],
                                'value'     =>  [],
                            ];
                            foreach ($softwares['wpVersion'] as $installedVersion) {
                                $wpVersions['label'][] = 'v'.$installedVersion->wp_version;
                                $wpVersions['value'][] = (int)$installedVersion->count;
                            }
                            ?>
                            <script>
                                var wpVersionsLabels = <?php echo json_encode($wpVersions['label']) ?>;
                                var wpVersionsValues = <?php echo json_encode($wpVersions['value']) ?>;
                            </script>
                            <div>
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Version</th>
                                        <th>Instance Count</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    foreach ($softwares['wpVersion'] as $installedVersion) {
                                        ?>
                                        <tr>
                                            <th>Ver. <?php echo $installedVersion->wp_version ?></th>
                                            <td><?php echo $installedVersion->count ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card bg-gradient-blue">
                        <div class="card-body">
                            <h3>PHP Versions</h3>
                            <div>
                                <canvas id="phpVersions" height="300"></canvas>
                            </div>
                            <?php
                            $phpVersions = [
                                'label'     =>  [],
                                'value'     =>  [],
                            ];
                            foreach ($softwares['phpVersion'] as $installedVersion) {
                                $phpVersions['label'][] = 'v'.$installedVersion->php_version;
                                $phpVersions['value'][] = (int)$installedVersion->count;
                            }
                            ?>
                            <script>
                                var phpVersionsLabels = <?php echo json_encode($phpVersions['label']) ?>;
                                var phpVersionsValues = <?php echo json_encode($phpVersions['value']) ?>;
                            </script>
                            <div>
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                    <tr>
                                        <th>Version</th>
                                        <th>Instance Count</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    foreach ($softwares['phpVersion'] as $phpVersion) {
                                        ?>
                                        <tr>
                                            <th>Ver. <?php echo $phpVersion->php_version ?></th>
                                            <td><?php echo $phpVersion->count ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
add_action('styles', function () {
    ?>
    <link href="<?php echo assets_url('libs/daterangepicker/daterangepicker.css') ?>" rel="stylesheet" type="text/css" />
        <?php
});
add_action('scripts', function () {
    ?>
    <script src="<?php echo assets_url('libs/daterangepicker/daterangepicker.js') ?>"></script>
    <script>
        $(function() {
            var overviewLineChart = document.getElementById("overviewLineChart").getContext("2d");
            var myChart = (Chart.defaults.font.family = "Be Vietnam Pro", new Chart(overviewLineChart, {
                    type: "line",
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        datasets: [{
                            label: "Monthly Report",
                            data: [12, 19, 13, 9, 12, 11, 12, 19, 13, 9, 12, 11],
                            backgroundColor: ["#22c55e"],
                            borderColor: ["#22c55e"],
                            borderWidth: 2,
                            borderDash: [3],
                            borderJoinStyle: "round",
                            borderCapStyle: "round",
                            pointBorderColor: "#22c55e",
                            pointRadius: 3,
                            pointBorderWidth: 1,
                            tension: .3
                        }, {
                            label: "Monthly Report",
                            data: [8, 12, 15, 11, 8, 14, 16, 13, 10, 7, 19, 16],
                            backgroundColor: ["#fac146"],
                            borderColor: ["#fac146"],
                            borderWidth: 2,
                            borderDash: [0],
                            borderJoinStyle: "round",
                            borderCapStyle: "round",
                            pointBorderColor: "#fac146",
                            pointRadius: 3,
                            pointBorderWidth: 1,
                            tension: .3
                        }]
                    },
                    options: {
                        maintainAspectRatio: !1,
                        plugins: {legend: {labels: {color: "#7c8ea7", font: {family: "Be Vietnam Pro"}}}},
                        scales: {
                            y: {
                                beginAtZero: !0,
                                ticks: {
                                    color: "#7c8ea7"
                                },
                                grid: {
                                    drawBorder: "border",
                                    color: "rgba(132, 145, 183, 0.15)",
                                    borderDash: [3],
                                    borderColor: "rgba(132, 145, 183, 0.15)"
                                }
                            },
                            x: {
                                ticks: {color: "#7c8ea7"},
                                grid: {
                                    display: !1,
                                    color: "rgba(132, 145, 183, 0.09)",
                                    borderDash: [3],
                                    borderColor: "rgba(132, 145, 183, 0.09)"
                                }
                            }
                        }
                    }
                }));

            var overviewDonut = document.getElementById("doughnut").getContext("2d");
            var myChart = (Chart.defaults.font.family = "Be Vietnam Pro", new Chart(overviewDonut, {
                    type: "doughnut",
                    data: {
                        labels: ["Desktops", "Laptop", "Tablets", "Mobiles"],
                        datasets: [{
                            data: [80, 50, 100, 121],
                            backgroundColor: ["#f67f7f", "#7777f0", "#fac146", "#22c55e"],
                            cutout: 100,
                            radius: 80,
                            borderColor: "transparent",
                            borderRadius: 0,
                            hoverBackgroundColor: ["#4d79f6", "#ff5da0", "#e0e7fd", "#4ac7ec"]
                        }]
                    },
                    options: {
                        maintainAspectRatio: !1,
                        plugins: {legend: {labels: {color: "#7c8ea7", font: {family: "Be Vietnam Pro"}}}}
                    }
                }));

            var start = moment().subtract(29, 'days');
            var end = moment();
            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);
            cb(start, end);
            var installedVersionContainer = document.getElementById("installedVersions").getContext("2d");
            var installedVersionsChart = (Chart.defaults.font.family = "Be Vietnam Pro", new Chart(installedVersionContainer, {
                type: "doughnut",
                data: {
                    labels: installedVersionsLabels,
                    datasets: [{
                        data: installedVersionsValues,
                        backgroundColor: ['#FF8C00','#8FBC8F','#4169E1','#DC143C','#00CED1','#FFD700','#A0522D','#40E0D0','#800080','#00FA9A','#CD5C5C','#1E90FF'],
                        cutout: 70,
                        radius: 100,
                        borderColor: "transparent",
                        borderRadius: 0,
                        hoverBackgroundColor: ["#4d79f6", "#ff5da0", "#e0e7fd", "#4ac7ec"]
                    }]
                },
                options: {
                    maintainAspectRatio: !1,
                    plugins: {legend: {labels: {color: "#7c8ea7", font: {family: "Be Vietnam Pro"}}}}
                }
            }));

            var wpVersionContainer = document.getElementById("wpVersions").getContext("2d");
            var wpVersionsChart = (Chart.defaults.font.family = "Be Vietnam Pro", new Chart(wpVersionContainer, {
                type: "doughnut",
                data: {
                    labels: wpVersionsLabels,
                    datasets: [{
                        data: wpVersionsValues,
                        backgroundColor: [
                            '#FF5733', '#33FF57', '#3357FF', '#F1C40F',
                            '#8E44AD', '#1ABC9C', '#E74C3C', '#2ECC71',
                            '#3498DB', '#E67E22', '#9B59B6', '#34495E'
                        ],
                        cutout: 80,
                        radius: 60,
                        borderColor: "transparent",
                        borderRadius: 0,
                        hoverBackgroundColor: ["#4d79f6", "#ff5da0", "#e0e7fd", "#4ac7ec"]
                    }]
                },
                options: {
                    maintainAspectRatio: !1,
                    plugins: {legend: {labels: {color: "#7c8ea7", font: {family: "Be Vietnam Pro"}}}}
                }
            }));

            var phpVersionContainer = document.getElementById("phpVersions").getContext("2d");
            var phpVersionsChart = (Chart.defaults.font.family = "Be Vietnam Pro", new Chart(phpVersionContainer, {
                type: "doughnut",
                data: {
                    labels: phpVersionsLabels,
                    datasets: [{
                        data: phpVersionsValues,
                        backgroundColor: ['#FF6F61','#6B5B95','#88B04B','#FFA07A','#20B2AA','#FFB347','#B22222','#3CB371','#6495ED','#DAA520','#FF69B4','#5F9EA0'],
                        cutout: 80,
                        radius: 60,
                        borderColor: "transparent",
                        borderRadius: 0,
                        hoverBackgroundColor: ["#4d79f6", "#ff5da0", "#e0e7fd", "#4ac7ec"]
                    }]
                },
                options: {
                    maintainAspectRatio: !1,
                    plugins: {legend: {labels: {color: "#7c8ea7", font: {family: "Be Vietnam Pro"}}}}
                }
            }));
        });
    </script>
    <?php
});
