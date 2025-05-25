<div class="card">
    <div class="card-body p-0 bg-black auth-header-box rounded-top">
        <div class="text-center p-3">
            <a href="<?php echo route('auth.login') ?>" class="logo logo-admin">
                <img src="<?php echo assets_url('images/logo-sm.png'); ?>" height="50" alt="logo" class="auth-logo">
            </a>
            <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Login</h4>
        </div>
    </div>
    <div class="card-body pt-0">
        <form class="my-4" action="" method="post">
            <div class="form-group mb-2">
                <label class="form-label" for="username">Username</label>
                <input type="text" class="form-control" id="username" name="email" placeholder="Enter username">
            </div><!--end form-group-->

            <div class="form-group">
                <label class="form-label" for="userpassword">Password</label>
                <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">
            </div><!--end form-group-->

            <div class="form-group row mt-3">
                <div class="col-sm-6">
                    <div class="form-check form-switch form-switch-success">
                        <input class="form-check-input" name="remember" type="checkbox" id="customSwitchSuccess">
                        <label class="form-check-label" for="customSwitchSuccess">Remember me</label>
                    </div>
                </div><!--end col-->
                <div class="col-sm-6 text-end">
                    <a href="<?php echo route('auth.forgot_password') ?>" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                </div><!--end col-->
            </div><!--end form-group-->

            <div class="form-group mb-0 row">
                <div class="col-12">
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary" type="submit">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                    </div>
                </div><!--end col-->
            </div> <!--end form-group-->
        </form><!--end form-->
    </div><!--end card-body-->
</div><!--end card-->