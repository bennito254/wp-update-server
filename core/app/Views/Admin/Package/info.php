<?php

/** @var \App\Entities\PackageEntity $package */

?>

<div class="card shadow-none">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3 class="d-inline-block">Details Sections</h3>
            <button class="btn btn-sm btn-purple" data-bs-toggle="modal" data-bs-target="#sectionEditor">New Section</button>
        </div><!--end col-->
    </div><!--end card-header-->
    <div class="card-body pt-0">
        <div class="row">
            <?php
            $sections = $package->getSections();

            ?>
            <div class="col-sm-3 p-0 bg-gradient-blue">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <?php
                    foreach ($sections as $key=>$section) {
                        ?>
                        <a class="nav-link rounded-0 waves-effect waves-light <?php echo $key == 'description' ? 'active' : '' ?>" id="v-<?php echo $key ?>-tab" data-bs-toggle="pill" href="#v-<?php echo $key ?>" role="tab" aria-controls="v-<?php echo $key ?>" aria-selected="false" tabindex="-1"><?php echo ucwords(str_replace('_', ' ', $section['name'])) ?></a>
                        <?php
                    }
                    ?>
                </div>
            </div><!--end col-->
            <div class="col-sm-9 p-0">
                <div class="tab-content mt-2" id="v-pills-tabContent">
                    <?php
                    foreach ($sections as $key=>$section) {
                        ?>
                        <div class="tab-pane fade <?php echo $key == 'description' ? 'active show' : '' ?>" id="v-<?php echo $key ?>" role="tabpanel" aria-labelledby="v-<?php echo $key ?>-tab">
                            <button class="btn btn-outline-primary btn-sm float-end editBtn" data-bs-toggle="modal" data-bs-target="#sectionEditor" data-content="<?php echo htmlentities($section['content']) ?>" data-section="<?php echo $key ?>" data-name="<?php echo $section['name'] ?>"><i class="iconoir-edit-pencil"></i> Edit</button>
                            <button class="btn btn-outline-danger btn-sm float-end send-to-server-click"
                                    loader="true" data="action:delete|section_id:<?php echo $key ?>" url="<?php echo route('admin.package.section.delete', $package->slug) ?>"
                                    warning-title="Delete Section" warning-message="Are you sure you want to delete this section?" warning-button="Yes"
                                    data-section="<?php echo $key ?>"><i class="iconoir-trash"></i> Delete</button>
                            <div class="quillEditor p-2">
                                <?php
                                echo $section['content'];
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div><!--end col-->
        </div> <!--end row-->
    </div><!--end card-body-->
</div>

<div class="card">
    <div class="card-body">
        <h4>Previous Versions</h4>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Version</th>
                        <th>Upload Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $n = 0;
                foreach ($package->getOtherVersions() as $version) {
                    $n++;
                    ?>
                    <tr>
                        <td><?php echo $n; ?></td>
                        <td><?php echo $version->version; ?></td>
                        <td><?php echo \CodeIgniter\I18n\Time::createFromTimestamp(strtotime($version->created_at))->format('F d, Y H:i:s'); ?></td>
                        <td>
                            Stream Download
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
<!-- Sections modal -->
<div class="modal fade bd-example-modal-lg" id="sectionEditor" tabindex="-1" aria-labelledby="myLargeModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="myLargeModalLabel">Edit Section</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body">
                <input type="hidden" id="editorSectionID" value="" />
                <div class="form-group">
                    <label class="form-label" for="editorSectionName">Section Name</label>
                    <input class="form-control mb-2" id="editorSectionName" value="" placeholder="Section Name" />
                </div>
                <div id="editEditor"></div>
                <button class="btn btn-success btnSaveQuill mt-3">Save Changes</button>
            </div><!--end modal-body-->
        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div>

<script>
    const quill = new Quill("#editEditor",{theme:"snow"});
    var sectionID = null;
    document.querySelectorAll('.editBtn').forEach(element => {
        element.addEventListener('click', function () {
            sectionID = element.getAttribute('id');
            document.querySelector('#editorSectionName').value = element.getAttribute('data-name')
            document.querySelector('#editorSectionID').value = element.getAttribute('data-section')
            quill.root.innerHTML = element.getAttribute('data-content');
        })
    })

    document.querySelector('.btnSaveQuill').addEventListener('click', function () {
        var html = quill.root.innerHTML;

        var e = {
            url: "<?php echo route('admin.package.section', $package->slug); ?>",
            data: "section_id="+document.querySelector('#editorSectionID').value+"&section_name="+document.querySelector('#editorSectionName').value+"&section_content="+html,
            loader: true
        };
        ajaxRequest(e, function(response) {
            console.log(response);
            if(response.status == 'error') {
                toast("Not Found", response.message, 'error');
            } else if(response.status == 'success') {
                toast("Success", "Section updated successfully", 'success');
                window.location.reload();
            } else {
                toast("Warning", "Something went wrong", 'error');
            }
        });
    })
</script>