<?php
namespace App\lib\traits;

trait JpegCompressor{

	public function optimizeJpeg($source_path, $destination_url){
		$jpegtran = $this->jpegTran($source_path, $destination_url);
		return $jpegtran;
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

	}
}