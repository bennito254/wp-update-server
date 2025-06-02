<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">
<head>
    <meta charset="utf-8"/>
    <title><?php echo $site_title ?: "Update Server"; ?> - <?php echo get_option('site_title', 'Update Server') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Wordpress Plugins and Themes self-hosted updates server" name="description"/>
    <meta content="Bennito254" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo assets_url('images/favicon.ico'); ?>">


    <!-- App css -->
    <link href="<?php echo assets_url('css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo assets_url('css/icons.min.css') ?>" rel="stylesheet" type="text/css"/>
    <link href="<?php echo assets_url('css/app.min.css') ?>" rel="stylesheet" type="text/css"/>
    <style>
        .alert {
            padding: 8px 8px !important;
            border-radius: 0;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 col-lg-8 col-xl-4 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                <div class="text-center p-3">
                                    <a href="<?php echo route('auth.login') ?>" class="logo logo-admin">
                                        <img src="<?php echo assets_url('images/logo-sm.png'); ?>" height="50"
                                             alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Login</h4>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <?php
                                $error = session()->getFlashdata('error');
                                $success = session()->getFlashdata('success');
                                $messages = session()->getFlashdata('message');
                                if ($error) {
                                    if (is_array($error)) {
                                        foreach ($error as $it) {
                                            ?>
                                            <div class="alert bg-danger"><?php echo $it; ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="alert bg-danger"><?php echo $error; ?></div>
                                        <?php
                                    }
                                }
                                if ($success) {
                                    ?>
                                    <div class="alert bg-success"><?php echo $success; ?></div>
                                    <?php
                                }
                                if ($messages) {
                                    if (is_array($messages)) {
                                        foreach ($messages as $message) {
                                            ?>
                                            <div class="alert bg-info"><?php echo $message ?></div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div><?php echo $messages ?></div>
                                        <?php
                                    }
                                }

                                echo $_html_content;
                                ?>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->
</div><!-- container -->
</body>
</html>