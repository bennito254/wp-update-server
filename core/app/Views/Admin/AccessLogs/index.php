<?php
$begin = \Config\Services::request()->getGet('start') ?? date('Y-m-d');
$finish = \Config\Services::request()->getGet('end') ?? date('Y-m-d');

$model = model(\App\Models\UpdateLogsModel::class);
$model->where('created_at >=', $begin.' 00:00:00');
$model->where('created_at <=', $finish.' 23:59:59');
$logs = $model->findAll();
?>

<div class="d-flex justify-content-between">
    <h1>Access Logs</h1>
    <div>
        <span id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </span>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php
        if (count($logs) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Site</th>
                        <th>Package</th>
                        <th>Action</th>
                        <th>Access Granted?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    foreach ($logs as $updateLog) {
                        $n++;
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><?php echo $updateLog->created_at ?></td>
                            <td><a href="<?php echo $updateLog->site_url ?>" target="_blank"><?php echo $updateLog->site_url ?></a> </td>
                            <td><a href="<?php echo route('admin.packages.view', $updateLog->slug) ?>"><?php echo $updateLog->slug ?></a></td>
                            <td><?php echo ucwords(str_replace(['-', '_',], ' ', $updateLog->action)) ?></td>
                            <td><?php echo $updateLog->access_granted == '1' ? '<span class="badge bg-success">YES</span>' : '<span class="badge bg-danger">NO</span>' ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
                <?php
        } else {
            ?>
            <div class="alert alert-warning">
                No logs found for the specified time
            </div>
            <?php
        }
        ?>

    </div>
</div>


<?php
add_action('scripts', function () {
    ?>
    <script>
        var begin = "<?php echo \Config\Services::request()->getGet('start') ?? date('Y-m-d') ?>";
        var finish = "<?php echo \Config\Services::request()->getGet('end') ?? date('Y-m-d') ?>";

        var start = moment(begin);
        var end = moment(finish);

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            if (start.format('YYYY-MM-DD') != begin && end.format('YYYY-MM-DD') != finish) {
                const url = new URL(window.location.href);
                url.searchParams.set("start", start.format('YYYY-MM-DD')); // Add or update the parameter
                url.searchParams.set("end", end.format('YYYY-MM-DD')); // Add or update the parameter
                window.location.href = url.toString(); // Reload with the new URL
            }
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment().subtract(0, 'days'), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        cb(start, end);
    </script>
    <?php
});
