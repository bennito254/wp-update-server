<?php


?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3>Security Settings</h3>
            </div>
            <div class="card-body">
                <form class="ajaxForm" autocomplete="off" action="<?php echo current_url(); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="">
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" class="form-control" name="old_password" value="" placeholder="Current Password" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">New Password</label>
                                    <input type="password" class="form-control" name="new_password" value="" placeholder="New Password" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirm_new_password" value="" placeholder="Confirm new Password" required="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

