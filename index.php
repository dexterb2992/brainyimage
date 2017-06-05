<?php 
    include "conf.php";
    include "views/partials/_header.php"; 
?>
    
    <div class="container">
        <section class="target img-zone text-center" id="img-zone">
            <div class="img-drop">
            	<figure class="icon"></figure>
	            <p class="lead">Drop your .png or .jpg files here!</p>
	            <p><small>Up to 20 images, max 5 MB each.</small></p>
	            <!-- <input type="file" multiple="multiple"> -->
	            <h2><i class="glyphicon glyphicon-camera"></i></h2>
                <span class="btn btn-success btn-file">
                Click to Open File Browser<input type="file" id="file_handle" multiple="multiple" accept="image/*">
                </span>
            </div>
        </section>
        <div class="results hidden list-group" id="results">

        </div>
    </div>
    <div class="container">
        <h1 class="cover-heading">Smart PNG and JPEG compression</h1>
        <p class="lead"><?= APP_NAME;?> uses smart lossy compression techniques to reduce the file size of your PNG files. By selectively decreasing the number of colors in the image, fewer bytes are required to store the data. The effect is nearly invisible but it makes a very large difference in file size!</p>
    </div>
<?php 
    include "views/partials/_footer.php"; 
?>
                    