<?php
namespace App\lib\traits;

trait JpegCompressor{

	public function jpegTran($source_path, $destination_url){
		$command = "jpegtran -copy none -optimize ".escapeshellarg($source_path)." > ".escapeshellarg($destination_url);
		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}

	public function jpegOptim($source_path, $destination_dir){
		$command = "jpegoptim --strip-all ".$source_path." -d ".$destination_dir;

		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}
}