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
                                <div class="col-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="<?php echo user()->first_name ?>" placeholder="First Name" required="">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="<?php echo user()->last_name ?>" placeholder="First Name" required="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" value="<?php echo user()->phone ?>" placeholder="Current Password" required="">
                                </div>
                            </div>
                        </div>

                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

