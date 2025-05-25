<div class="row justify-content-center">
    <div class="col-md-6 col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Line Chart</h4>
                    </div><!--end col-->
                </div>  <!--end row-->
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <canvas id="lineChart" width="300" height="300"></canvas>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Donut Chart</h4>
                    </div><!--end col-->
                </div>  <!--end row-->
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <canvas id="doughnut" height="300"></canvas>
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
</div><!--end row-->


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h2 class="h4">Active Sites</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Site URL</th>
                            <th>IP Address</th>
                            <th>WP Version</th>
                            <th>PHP Version</th>
                            <th>Installed Version</th>
                            <th>Last Update Request</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($package->getSitesInstalled() as $site) {
                            $n++;
                            ?>
                            <tr>
                                <td><?php echo $n; ?></td>
                                <td><a href="<?php echo $site->site_url; ?>" target="_blank"><?php echo $site->site_url; ?></a></td>
                                <td><?php echo $site->ip; ?></td>
                                <td><?php echo $site->wp_version; ?></td>
                                <td><?php echo $site->php_version; ?></td>
                                <td><?php echo $site->installed_version; ?></td>
                                <td><?php echo $site->created_at; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>