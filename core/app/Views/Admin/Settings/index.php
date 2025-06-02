<?php


?>
<div class="d-flex justify-content-between">
    <h1>System Settings</h1>
</div>
<div class="card bg-transparent">
    <div class="card-body bg-light p-0">
        <?php
        $page = \Config\Services::request()->getGet('page');
        $pages = ['general', 'email'];
        $page = $page ?: 'general';
        if (!in_array($page, $pages)) {
            $page = 'general';
        }
        ?>
        <div class="mt-2 mb-2">
            <nav class="nav nav-pills nav-justified">
                <a class="nav-item rounded-0 nav-link <?php echo $page == 'general' ? 'active' : '' ?>" href="<?php echo routeAddGetParams(current_url(), ['page' => 'general']) ?>">General Settings</a>
                <a class="nav-item rounded-0 nav-link <?php echo $page == 'email' ? 'active' : '' ?>" href="<?php echo routeAddGetParams(current_url(), ['page' => 'email']) ?>">E-Mail Settings</a>
            </nav>
        </div>
    </div>
    <div class="card-body bg-transparent">
        <?php

        try {
            echo view('Admin/Settings/'.$page, []);
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
