<?php
namespace App\lib\traits;

use App\lib\PNGQuant;
use App\lib\Helper;

use App\lib\traits\JpegCompressor;

trait ImageCompressor {
	use JpegCompressor;

	public function compressJPEG($source_path, $destination_url, $quality){
		$info = getimagesize($source_path);

		if ($info['mime'] == 'image/jpeg'){
			return $this->optimizeJpeg($source_path, $destination_url);
		}

		return false;
	}

	public function compressPNG($source_path, $destination_path, $quality){
		

		$instance = new PNGQuant();

		// Change the path to the binary of pngquant, for example in windows would be (with an example path):
		// $instance->setBinaryPath("E:\\wamp64\\www\\brainyimage\\pngquant\\pngquant.exe")
		// $instance->setBinaryPath("/usr/local/bin/pngquant")
		$instance->setBinaryPath(env("pngquant"))
			->execute();

		// Set the path to the image to compress
		$result = $instance->setImage($source_path)
		    // Overwrite output file if exists, otherwise pngquant will generate output ...
		    ->overwriteExistingFile()
		    // As the quality in pngquant isn't fixed (it uses a range)
		    // set the quality between 50 and 80
		    ->setQuality(60, $quality)
		    // Retrieve RAW data from pngquant
		    ->getRawOutput();

		$exit_code = $result["statusCode"];


		// if exit code is equal to 0 then everything went right !
		if($exit_code == 0){

		    $rawImage = imagecreatefromstring($result["imageData"]);

		    // Example Save the PNG Image from the raw data into a file or do whatever you want.
		    imagepng($rawImage , $destination_path);

		}else{
		    return array(
		    	"success" => 0, 
		    	"source_path" => $source_path,
		    	"file_info" => parse_url($source_path), 
		    	"error" => "Something went wrong (status code $exit_code)  with description: ". $instance->getErrorTable()[(string) $exit_code],
		    	'error_description' => $instance->getErrorTable()[(string) $exit_code]
		    );
		}

		
	}	

}


// /usr/bin/jpegtran
// /usr/local/bin/jpegoptim
// /usr/local/bin/optipng
// /usr/bin/pngcrush
// /usr/bin/pngout