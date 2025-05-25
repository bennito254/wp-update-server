<?php


?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h4 class="">Access Management</h4>

            <div class="form-check form-switch form-switch-success">
                <input class="form-check-input" type="checkbox" onchange="updatePackageOption($(this))" data-package-id="<?php echo $package->id ?>" data-option-name="allow_access_for_new_sites" value="1" id="customSwitchSuccess" <?php echo $package->getOption('allow_access_for_new_sites', '0') == '1' ? 'checked=""' : '' ?>>
                <label class="form-check-label" for="customSwitchSuccess">Allow Access for new sites</label>
            </div>
        </div>
            <div class="table-responsive mt-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Site URL</th>
                            <th>IP Address</th>
                            <th>Installed Version</th>
                            <th>PHP Version</th>
                            <th>WP Version</th>
                            <th>Allow Access</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 0;
                    $sites = $package->getSitesInstalled();
                    foreach ($sites as $site) {
                        $n++;
                        $domain = getFullDomain($site->site_url);
                        ?>
                        <tr>
                            <td><?php echo $n; ?></td>
                            <td><a href="<?php echo $site->site_url; ?>" target="_blank"><?php echo $domain; ?></a> </td>
                            <td><?php echo $site->ip; ?></td>
                            <td><?php echo $site->installed_version; ?></td>
                            <td><?php echo $site->php_version; ?></td>
                            <td><?php echo $site->wp_version; ?></td>
                            <td>
                                <div class="form-check form-switch form-switch-success">
                                    <input class="form-check-input" type="checkbox" onchange="updatePackageOption($(this))" data-package-id="<?php echo $package->id ?>" data-option-name="<?php echo 'allow_'.$domain; ?>" value="1" id="customSwitchSuccess" <?php echo $package->getOption('allow_'.$domain, '0') == '1' ? 'checked=""' : '' ?>>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<?php
add_action('scripts', function () use ($package) {
    ?>
    <script>
        function updatePackageOption(element) {
            var packageID = $(element).data('package-id');
            var option = $(element).data('option-name');

            var e = {
                loader: true,
                url: "<?php echo route('admin.package.options.toggle', $package->slug) ?>",
                data: "option="+option+"&action=toggle&value="+element.val()
            }
            ajaxRequest(e, function (response) {
                serverResponse(response);
            })
        }
    </script>
    <?php
});