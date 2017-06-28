<?php 
    include "conf.php";
    include "views/partials/_header.php"; 
?>


    <!-- Begin page content -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <section class="target img-zone text-center" id="img-zone">
                    <div class="img-drop">
                        <figure class="icon"></figure>
                        <h4>Drop your .png or .jpg files here!</h4>
                        <p><small>Up to 20 images, max 5 MB each.</small></p>
                        <h2><i class="fa fa-cloud-upload"></i></h2>
                        <span class="btn btn-success btn-file">
                        Click to Open File Browser<input type="file" id="file_handle" multiple="multiple" accept="image/*">
                        </span>
                    </div>
                </section>
                <div class="results hidden" id="results"></div>
            </div>
        </div>

        <div class="mt-3 text-center">
            <h1>Smart PNG and JPEG compression</h1>
        </div>
        <p class="lead text-center"><?= APP_NAME;?> uses smart lossy compression techniques to reduce the file size of your PNG files. By selectively decreasing the number of colors in the image, fewer bytes are required to store the data. The effect is nearly invisible but it makes a very large difference in file size!</p>
    </div>
    <div class="clear"></div>
    <br/>
    <br/>

    <div class="modal fade" tabindex="-1" role="dialog" id="image_difference_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Modal title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>One fine body&hellip;</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <?php include "views/partials/_footer.php"; ?>
