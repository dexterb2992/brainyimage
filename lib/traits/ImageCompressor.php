<?php
namespace App\lib\traits;

use App\lib\PNGQuant;
use App\lib\Helper;

use App\lib\traits\JpegCompressor;

trait ImageCompressor {
	use JpegCompressor;

	public function compressJPEG($source_path, $destination_dir){
		$info = getimagesize($source_path);

		// $source_path = escapeshellarg($source_path);
		// $destination_url = escapeshellarg($destination_url);
		mkdir( $destination_dir."jpegtran");
		mkdir( $destination_dir."jpegoptim");
		$jpegtran_dest = $destination_dir."jpegtran/".basename($source_path);
		$jpegoptim_dest = $destination_dir."jpegoptim/".basename($source_path);

		$jpegTran = $this->jpegTran($source_path, $jpegtran_dest);
		$jpegOptim = $this->jpegOptim($source_path, $jpegoptim_dest);

		sleep(3);

		$size1 = $jpegTran != false ?  filesize($jpegTran) : 0;
		$size2 = $jpegOptim != false ? filesize($jpegOptim) : 0;
		
		// save to logs
		$cmd = "jpegoptim --strip-all ".escapeshellarg($source_path)." -d ".$jpegoptim_dest;
		file_put_contents("./logs/Jpeg.log", "jpegTran: $size1, jpegOptim: $size2".PHP_EOL."
				 jpegTran: $jpegTran,".PHP_EOL." jpegOptim: $jpegOptim".PHP_EOL."cmd: $cmd");

		if( $jpegOptim == false && $jpegTran == false ) return false;

		if( $size1 > $size2 && $size2 != 0 )
			return $jpegOptim;
		else
			return $jpegTran;

		return false;
	}

	public function compressPNG($source_path, $destination_path, $quality){
		

		$instance = new PNGQuant();

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

}