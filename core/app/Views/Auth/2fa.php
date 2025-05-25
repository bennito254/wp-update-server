<div class="card">
    <div class="card-body p-0 bg-black auth-header-box rounded-top">
        <div class="text-center p-3">
            <a href="<?php echo route('auth.login') ?>" class="logo logo-admin">
                <img src="<?php echo assets_url('images/logo-sm.png'); ?>" height="50" alt="logo" class="auth-logo">
            </a>
            <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">OTP</h4>
        </div>
    </div>
    <div class="card-body pt-0">
        <!-- Form -->
        <form autocomplete="off" method="post" autocapitalize="off" class="js-validate needs-validation mb-2" novalidate="">
            <?php
            //$authenticator = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            //$qrCodeURL = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate(APP_NAME, $secret, $_SERVER['SERVER_NAME']);
            ?>
            <div class="mb-4">
                <p>Check your Authenticator app for the OTP</p>
            </div>

            <div class="mb-4">
                <label class="form-label w-100" for="signupSrPassword" tabindex-itembackup="0">
                  <span class="d-flex justify-content-between align-items-center">
                    <span>OTP</span>
                  </span>
                </label>

                <div class="input-group" data-hs-validation-validate-class="">
                    <input type="text" class="form-control form-control-lg" name="otp" placeholder="Authenticator OTP" required="">

                </div>
            </div>
            <!-- End Form -->

            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">VERIFY</button>
            </div>
        </form>
        <a class="d-block text-success mt-4" href="<?php echo route('auth.login') ?>">Back to login</a>
        <!-- End Form -->
    </div><!--end card-body-->
</div><!--end card-->