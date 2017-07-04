<?php
namespace App\lib\traits;

trait JpegCompressor{

	public function optimizeJpeg($source_path, $destination_url){
		// $source_path = escapeshellarg($source_path);
		// $destination_url = escapeshellarg($destination_url);

		$jpegTran = $this->jpegTran($source_path, $destination_url);
		$jpegOptim = $this->jpegOptim($source_path, $destination_url);

		$dir = "./";

		$size1 = $jpegTran != false ?  filesize($dir.$jpegTran) : 0;
		$size2 = $jpegOptim != false ? filesize($dir.$jpegOptim) : 0;
		sleep(2);
		// save to logs
		file_put_contents("./logs/Jpeg.log", "jpegTran: $size1, jpegOptim: $size2\n
				 jpegTran: $jpegTran,\n jpegOptim: $jpegOptim");

		if( $size1 > $size2 )
			return $jpegOptim;
		else
			return $jpegTran;

		return false;
	}

	private function jpegTran($source_path, $destination_url){
		$command = "jpegtran -copy none -progressive -optimize ".escapeshellarg($source_path)." > ".escapeshellarg($destination_url);
		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}

	private function jpegOptim($source_path, $destination_url){
		$command = "jpegoptim --strip-all --all-progressive ".escapeshellarg($source_path)." --dest=".escapeshellarg($destination_url);

		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}
}