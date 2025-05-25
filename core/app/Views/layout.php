<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">
<head>
        <meta charset="utf-8" />
        <title><?php echo $site_title ?: "Update Server"; ?> - WP Update Server</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Bennito254" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo assets_url('images/favicon.ico'); ?>">
        <!-- App css -->
        <link href="<?php echo assets_url('css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo assets_url('css/icons.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo assets_url('css/app.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo assets_url('css/custom.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo assets_url('libs/quill/quill.snow.css'); ?>" rel="stylesheet" type="text/css" />
        <script src="<?php echo assets_url('libs/quill/quill.js'); ?>"></script>
        <script src="<?php echo assets_url('libs/jquery/jquery.min.js'); ?>"></script>
        <?php
        do_action('css');
        do_action('styles');
        do_action('stylesheet');
        ?>
    </head>
    
    <!-- Top Bar Start -->
    <body>
        <!-- Top Bar Start -->
        <div class="topbar d-print-none shadow-sm">
            <div class="container-fluid">
                <!-- Top Navbar -->
                <nav class="topbar-custom navbar navbar-expand-lg bg-light" id="topbar-custom">
                    <div class="container-fluid">

                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTop" aria-controls="navbarTop" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                        <div class="brand">
                            <a href="<?php echo site_url(); ?>" class="logo">
                                    <span>
                                        <img src="<?php echo assets_url('images/logo-sm.png'); ?>" style="max-height: 30px" alt="logo-small" class="logo-sm">
                                    </span>
                                <span class="">
                                        <img src="<?php echo assets_url('images/logo-light.png'); ?>" alt="logo-large" class="logo-lg logo-light">
                                        <img src="<?php echo assets_url('images/logo-dark.png'); ?>" alt="logo-large" class="logo-lg logo-dark">
                                    </span>
                            </a>
                        </div>
                        <div class="collapse navbar-collapse" id="navbarTop">
                            <ul class="navbar-nav mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo route('admin.dashboard') ?>">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo route('admin.packages.index') ?>">Packages</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Logs</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Settings</a>
                                </li>
                            </ul>
                        </div>
                        <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                            <li class="topbar-item">
                                <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                                    <i class="icofont-moon fs-6 dark-mode"></i>
                                    <i class="icofont-sun fs-6 light-mode"></i>
                                </a>
                            </li>
                            <li class="dropdown topbar-item">
                                <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                                   aria-haspopup="false" aria-expanded="false">
                                    <img src="<?php echo user()->avatar; ?>" alt="" class="thumb-md rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end py-0">
                                    <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                        <div class="flex-shrink-0">
                                            <img src="<?php echo user()->avatar; ?>" alt="" class="thumb-md rounded-circle">
                                        </div>
                                        <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                            <h6 class="my-0 fw-medium text-dark fs-13"><?php echo user()->name; ?></h6>
                                            <small class="text-muted mb-0">Administrator</small>
                                        </div><!--end media-body-->
                                    </div>
                                    <div class="dropdown-divider mt-0"></div>
                                    <small class="text-muted px-2 py-1 d-block">Settings</small>
                                    <a class="dropdown-item" href="pages-profile.html"><i class="las la-cog fs-18 me-1 align-text-bottom"></i>Account Settings</a>
                                    <a class="dropdown-item" href="pages-profile.html"><i class="las la-lock fs-18 me-1 align-text-bottom"></i> Security</a>
                                    <div class="dropdown-divider mb-0"></div>
                                    <a class="dropdown-item text-danger" href="<?php echo route('auth.logout') ?>"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>


        <!-- Top Bar End -->
        <div class="page-wrapper">

            <!-- Page Content-->
            <div class="page-content">
                <div class="container-fluid">
                    <?php
                    echo $_html_content;
                    ?>
                </div><!-- container -->
                
                <!--Start Rightbar-->
                <!--Start Rightbar/offcanvas-->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="Appearance" aria-labelledby="AppearanceLabel">
                    <div class="offcanvas-header border-bottom justify-content-between">
                      <h5 class="m-0 font-14" id="AppearanceLabel">Appearance</h5>
                      <button type="button" class="btn-close text-reset p-0 m-0 align-self-center" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">  
                        <h6>Account Settings</h6>
                        <div class="p-2 text-start mt-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch1">
                                <label class="form-check-label" for="settings-switch1">Auto updates</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch2" checked>
                                <label class="form-check-label" for="settings-switch2">Location Permission</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="settings-switch3">
                                <label class="form-check-label" for="settings-switch3">Show offline Contacts</label>
                            </div><!--end form-switch-->
                        </div><!--end /div-->
                        <h6>General Settings</h6>
                        <div class="p-2 text-start mt-3">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch4">
                                <label class="form-check-label" for="settings-switch4">Show me Online</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="settings-switch5" checked>
                                <label class="form-check-label" for="settings-switch5">Status visible to all</label>
                            </div><!--end form-switch-->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="settings-switch6">
                                <label class="form-check-label" for="settings-switch6">Notifications Popup</label>
                            </div><!--end form-switch-->
                        </div><!--end /div-->               
                    </div><!--end offcanvas-body-->
                </div>
                <!--end Rightbar/offcanvas-->
                <!--end Rightbar-->
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        <!-- Javascript  -->  
        <!-- vendor js -->
        
        <script src="<?php echo assets_url('libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
        <script src="<?php echo assets_url('libs/iconify-icon/iconify-icon.min.js'); ?>"></script>
        <script src="<?php echo assets_url('libs/simplebar/simplebar.min.js'); ?>"></script>
        <script src="<?php echo assets_url('js/moment.js'); ?>"></script>
        <script src="<?php echo assets_url('libs/chart.js/chart.min.js'); ?>"></script>
        <script src="<?php echo assets_url('js/pages/chartjs.init.js'); ?>"></script>
        <script src="<?php echo assets_url('js/app.js'); ?>"></script>
        <script src="<?php echo assets_url('js/custom.js'); ?>"></script>
        <?php
        do_action('scripts');
        do_action('js');
        do_action('script');
        ?>
    </body>
    <!--end body-->
</html>
