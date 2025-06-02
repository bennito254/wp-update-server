
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3>E-Mail Settings</h3>
                </div>
                <div class="card-body">
                    <form class="ajaxForm" autocomplete="off" action="<?php echo current_url(); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                        <div class="">
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Mail Host</label>
                                        <input type="url" class="form-control" name="email_settings_host" value="<?php echo old('email_settings_host', get_option('email_settings_host')); ?>" placeholder="eg: mail.domain.com" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">SMTP E-Mail Address</label>
                                        <input type="email" class="form-control" name="email_settings_email_address" value="<?php echo old('email_settings_email_address', get_option('email_settings_email_address')); ?>" placeholder="eg:test@domain.com" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">SMTP E-Mail Password</label>
                                        <input type="password" class="form-control" name="email_settings_email_password" value="<?php echo old('email_settings_email_password', get_option('email_settings_email_password')); ?>" placeholder="...password" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">SMTP Port</label>
                                                <input type="number" class="form-control" min="1" max="65355" name="email_settings_port" value="<?php echo old('email_settings_port', get_option('email_settings_port', 465)); ?>" placeholder="SMTP port" required="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Encryption Type</label>
                                                <select class="form-control select2" name="email_settings_encryption" required="">
                                                    <option <?php echo get_option('email_settings_encryption') == 'ssl' ? 'selected' : ''; ?> value="ssl">SSL (Recommended)</option>
                                                    <option <?php echo get_option('email_settings_encryption') == 'tls' ? 'selected' : ''; ?> value="tls">TLS</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Reply To</label>
                                        <input type="url" class="form-control" name="email_settings_reply_to" value="<?php echo old('email_settings_reply_to', get_option('email_settings_reply_to')); ?>" placeholder="eg: mail@domain.com">
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
                <div class="card-header">
                    <h3>Test E-Mail Settings</h3>
                </div>
                <div class="card-body">
                    <div>
                        <form method="post" class="ajaxForm" loader="true" data-parsley-validate="" action="<?php echo route('admin.settings.test_email') ?>">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">To</span>
                                <input type="email" class="form-control" name="email" placeholder="Email address" value="<?php echo user()->email ?>" required="">
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Subject</span>
                                <input type="text" class="form-control" name="subject" placeholder="Subject" required="" value="Test E-Mail">
                            </div>
                            <div class="form-group mb-3">
                                <label>Message</label>
                                <textarea rows="4" class="form-control" name="message" required="">This is a test email</textarea>
                            </div>
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-send"></i> Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
