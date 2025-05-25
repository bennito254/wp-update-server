<?php
/** @var \App\Entities\PackageEntity $package */

?>

<div class="row">
    <div class="col-md-9 col-sm-9">
        <div class="card bg-transparent shadow-none">
            <div class="card-body border-bottom" style="background: url('<?php echo $package->getBanners()['high']; ?>') no-repeat center center; background-size: cover; position: relative; color: white">
                <div style="position: absolute;top: 0; left: 0;right: 0;bottom: 0;background-color: rgba(0, 0, 0, 0.5);z-index: 1;"></div>
                <div style="position: relative; z-index: 2;">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="<?php echo $package->getIcons()['2x'] ?>" style="max-height: 100px" alt="" class="rounded-circle">
                        </div>
                        <div class="flex-grow-1 ms-3 text-truncate">
                            <h1><?php echo $package->title; ?></h1>
                            <span class="text-primary fs-6">Current Version: <?php echo $package->version ?></span> |
                            <span class="text-info-emphasis fs-6">Last Updated: <?php echo DateTime::createFromFormat('Y-m-d H:i:s', $package->metadata['last_updated'])->format('F d, Y H:i:s') ?></span>
                        </div><!--end media-body-->
                    </div>
                </div>
            </div>
            <div class="card-body bg-transparent p-0">
                <?php
                $page = \Config\Services::request()->getGet('page');
                $pages = ['overview', 'info', 'access', 'settings'];
                $page = $page ?: 'overview';
                if (!in_array($page, $pages)) {
                    $page = 'overview';
                }
                ?>
                <div class="mt-2 mb-2">
                    <nav class="nav nav-pills nav-justified">
                        <a class="nav-item rounded-0 nav-link <?php echo $page == 'overview' ? 'active' : '' ?>" href="<?php echo routeAddGetParams(current_url(), ['page' => 'overview']) ?>">Overview</a>
                        <a class="nav-item rounded-0 nav-link <?php echo $page == 'info' ? 'active' : '' ?>" href="<?php echo routeAddGetParams(current_url(), ['page' => 'info']) ?>">Package Info</a>
                        <a class="nav-item rounded-0 nav-link <?php echo $page == 'access' ? 'active' : '' ?>" href="<?php echo routeAddGetParams(current_url(), ['page' => 'access']) ?>">Access Management</a>
                        <a class="nav-item rounded-0 nav-link <?php echo $page == 'settings' ? 'active' : '' ?>" href="<?php echo routeAddGetParams(current_url(), ['page' => 'settings']) ?>">Settings</a>
                    </nav>
                </div>

                <?php

                try {
                    echo view('Admin/Package/'.$page, ['package' => $package]);
                } catch (Exception $e) {
                    ?>
                    <div class="alert alert-danger">
                        Page not found
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-3">
        <div class="card bg-gradient-blue">
            <div class="card-body" style="max-height: 800px; overflow-y: scroll">
                <?php
                $meta = $package->getMetadata();
                unset($meta['sections'], $meta['rating'], $meta['num_ratings'], $meta['upgrade_notice'], $meta['description'], $meta['keywords']);
                ?>
                <table class="table table-sm table-bordered">
                    <?php
                    foreach ($meta as $key => $value) {
                        ?>
                        <tr>
                            <th><?php echo ucwords(str_replace('_', ' ', $key)) ?></th>
                            <td><?php echo $value ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <th>Download Link</th>
                        <td><a href="<?php echo $package->generateDownloadUrl() ?>" target="_blank"><?php echo $package->generateDownloadUrl() ?></a></td>
                    </tr>
                    <tr>
                        <th>Update Link</th>
                        <td><a href="<?php echo $package->generateUpdateUrl() ?>" target="_blank"><?php echo $package->generateUpdateUrl() ?></a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

