<?php

use App\Models\PackagesModel;

$packages = model(PackagesModel::class)->findAll();
?>


<div class="row vh-100">
    <div class="col-md-9 order-2 order-md-1">
        <div class="card">
            <div class="card-body overflow-y-scroll">
                <h2>Packages</h2>
                <?php
                $packages = model(PackagesModel::class)->findAll();
                if (count($packages) > 0) {
                    ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Latest Version</th>
                                <th>Times Downloaded</th>
                                <th>Active Installs</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $n = 0;
                            foreach ($packages as $package) {
                                $n++;
                                ?>
                                <tr>
                                    <td><?php echo $n; ?></td>
                                    <td><?php echo $package->title; ?></td>
                                    <td><?php echo $package->version; ?></td>
                                    <td><?php echo '-'; ?></td>
                                    <td><?php echo $package->getActiveInstalls(); ?></td>
                                    <td><a class="btn btn-sm btn-info" href="<?php echo route('admin.packages.view', $package->slug) ?>">View</a> </td>
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
                        No packages uploaded
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3 order-1 order-md-2">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <h4 class="card-title">Upload Package</h4>
                </div>  <!--end row-->
            </div><!--end card-header-->
            <div class="card-body pt-0">
                <form method="post" action="<?php echo route('admin.package.create') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="_file_upload" value="bennito">
                    <div class="form-group mb-3">
                        <label for="plugin" class="form-label">Package (Zip File) <span class="text-danger">*</span> </label>
                        <input type="file" class="form-control" accept=".zip" name="plugin" required="required" placeholder="Please select a file" />
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div><!--end card-body-->
        </div><!--end card-->
    </div>
</div>
