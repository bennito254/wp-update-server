
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3>General Settings</h3>
            </div>
            <div class="card-body">
                <form class="ajaxForm" autocomplete="off" action="<?php echo current_url(); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="">
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Site Name</label>
                                    <input type="text" class="form-control" name="site_title" value="<?php echo old('site_title', get_option('site_title', 'Update Server')); ?>" placeholder="eg: Update Server" required="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                // Created By: Bennito254
            </div>
        </div>
    </div>
</div>
