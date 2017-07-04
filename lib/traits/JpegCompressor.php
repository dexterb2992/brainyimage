<?php
namespace App\lib\traits;

trait JpegCompressor{

	public function jpegTran($source_path, $destination_url){
		$command = "jpegtran -copy none -progressive -optimize ".escapeshellarg($source_path)." > ".escapeshellarg($destination_url);
		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}

	public function jpegOptim($source_path, $destination_url){
		$command = "jpegoptim --strip-all --all-progressive ".escapeshellarg($source_path)." -d ".escapeshellarg($destination_url);

		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}
}