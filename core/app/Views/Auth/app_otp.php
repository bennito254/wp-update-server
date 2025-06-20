
        <form autocomplete="off" method="post" autocapitalize="off" class="js-validate needs-validation mb-2" novalidate="">
            <div class="mb-4">
                <p>Check your Authenticator app for the OTP or use the link we have sent to your email address</p>
            </div>

            <div class="mb-4">
                <label class="form-label w-100" for="signupSrPassword" tabindex-itembackup="0">
                  <span class="d-flex justify-content-between align-items-center">
                    <span>New Password</span>
                  </span>
                </label>
                <div class="input-group" data-hs-validation-validate-class="">
                    <input type="password" class="form-control form-control-lg" name="new_password" placeholder="New password" required="">

                </div>
            </div>
            <div class="mb-4">
                <label class="form-label w-100" for="signupSrPassword" tabindex-itembackup="0">
                  <span class="d-flex justify-content-between align-items-center">
                    <span>Confirm Password</span>
                  </span>
                </label>
                <div class="input-group" data-hs-validation-validate-class="">
                    <input type="password" class="form-control form-control-lg" name="confirm_password" placeholder="New password" required="">

                </div>
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
                <button type="submit" class="btn btn-success btn-lg">Reset Password</button>
            </div>
        </form>
        <a class="d-block text-success mt-4" href="<?php echo route('auth.login') ?>">Back to login</a>
        <!-- End Form -->