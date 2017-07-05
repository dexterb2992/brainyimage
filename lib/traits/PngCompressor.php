<?php
namespace App\lib\traits;

use App\lib\PNGQuant;

trait PngCompressor{

	public function pngQuant(){
		$instance = new PNGQuant($source_path, $destination_path);

		// Change the path to the binary of pngquant, for example in windows would be (with an example path):
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

	public function pngCrush($source_path, $destination_path){
		$command = "pngcrush -rem gAMA -rem cHRM -rem iCCP -rem sRGB -brute -q -l 9 -reduce -ow ".escapeshellarg($source_path, escapeshellarg($destination_path));

		system($command);
		if( file_exists($destination_path) )
			return $destination_path;
		return false;
	}
}