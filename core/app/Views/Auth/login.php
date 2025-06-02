<form class="my-2" action="" method="post">
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