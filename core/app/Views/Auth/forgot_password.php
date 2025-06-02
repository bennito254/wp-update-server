
        <form class="my-2" action="" method="post">
            <div class="form-group mb-2">
                <label class="form-label" for="username">Email</label>
                <input type="text" class="form-control" id="username" name="email" placeholder="Enter email address">
            </div><!--end form-group-->

            <div class="form-group mb-0 row">
                <div class="col-12">
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary" type="submit">Send Recovery Link <i class="fas fa-sign-in-alt ms-1"></i></button>
                    </div>
                </div><!--end col-->
            </div> <!--end form-group-->
        </form><!--end form-->
        <div class="form-group row mt-3">
            <div class="col-sm-6">

            </div><!--end col-->
            <div class="col-sm-6 text-end">
                <a href="<?php echo route('auth.login') ?>" class="text-muted font-13"><i class="dripicons-lock"></i> Back to Login</a>
            </div><!--end col-->
        </div><!--end form-group-->