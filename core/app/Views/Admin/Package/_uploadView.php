<?php


$maxFileSize = getMinimumUploadLimit()
?>

<!--<div class="card">-->
<!--    <div class="card-header">-->
<!--        <div class="row align-items-center">-->
<!--            <h4 class="card-title">Upload Package</h4>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="card-body pt-0">-->
<!--        <form class="ajaxForm" loader="true" method="post" action="--><?php //echo route('admin.package.create') ?><!--" data-parsley-validate enctype="multipart/form-data">-->
<!--            <input type="hidden" name="_file_upload" value="bennito">-->
<!--            <div class="form-group mb-3">-->
<!--                <label for="plugin" class="form-label">Package (Zip File) <span class="text-danger">*</span> (Max File Size: --><?php //echo $maxFileSize ?><!--)</label>-->
<!--                <input type="file" class="form-control" accept=".zip" name="plugin" required="required" data-parsley-max-file-size="--><?php //echo $maxFileSize ?><!--" placeholder="Please select a file" />-->
<!--            </div>-->
<!--            <button type="submit" class="btn btn-primary">Upload</button>-->
<!--        </form>-->
<!--    </div>-->
<!--</div>-->

<div class="text-center mb-3">
    <h4 class="text-uppercase mb-1">Upload Package</h4>
    <span>(Max File Size: <?php echo $maxFileSize ?>)</span>
    <div class="mt-2" id="uppy-container"></div>
</div>

<?php
add_action('styles', function () {
    ?>
    <link href="<?php echo assets_url('libs/uppy/uppy.min.css') ?>" rel="stylesheet" type="text/css" />
        <?php
});
add_action('scripts', function () use ($maxFileSize) {
    ?>
    <script src="<?php echo assets_url('libs/uppy/uppy.legacy.min.js') ?>"></script>
    <script>
        var uppy = new Uppy.Uppy({
            restrictions: {
                maxNumberOfFiles: 1,
                allowedFileTypes: ['.zip'],
                maxFileSize: <?php echo (int)$maxFileSize ?> * 1024 * 1024
            },
            autoProceed: true
        })
            .use(Uppy.Dashboard, {
                inline: true,
                target: "#uppy-container",
                note: 'Only one .zip file allowed (max 8MB)',
                showProgressDetails: true,
                locale: {
                    strings: {
                        dropPasteFiles: 'Drop your .zip file here or %{browse}',
                        browse: 'choose a file',
                        addMoreFiles: 'Add another file',
                        uploading: 'Uploading…',
                        uploadComplete: 'Upload complete!',
                        failedToUpload: 'Upload failed. Please try again.',
                        youCanOnlyUploadX: 'You can only upload %{smart_count} file.',
                        exceedsSize: 'File is too large. Maximum file size is %{size}.',
                        youCanOnlyUploadFileTypes: 'Only ZIP files are allowed.',
                    }
                }
            })
            .use(Uppy.XHRUpload, {
                endpoint: "<?php echo route('admin.package.create') ?>", // your PHP upload URL
                fieldName: 'plugin',   // custom field name
                formData: true         // send as multipart/form-data
            });

        uppy.on("complete", (result) => {
            result.successful.forEach(file => {
                // The parsed JSON response from your PHP server
                const response = file.response && file.response.body;
                if (response) {
                    serverResponse(response);
                }
            });
        });
        uppy.on("restriction-failed", (file, error) => {
            toast("Error!", error.message, 'error');
        });
    </script>

    <?php
});
