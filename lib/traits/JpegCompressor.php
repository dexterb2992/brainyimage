<?php
namespace App\lib\traits;

trait JpegCompressor{

	public function optimizeJpeg($source_path, $destination_url){
		$source_path = escapeshellarg($source_path);
		$destination_url = escapeshellarg($destination_url);

		$jpegTran = $this->jpegTran($source_path, $destination_url);
		$jpegOptim = $this->jpegOptim($source_path, $destination_url);

		$size1 = $jpegTran != false ?  $size1 = @filesize($jpegTran) : 0;
		$size2 = $jpegOptim != false ? $size2 = @filesize($jpegOptim) : 0;

		if( $size1 > $size2 )
			return $jpegOptim;
		else
			return $jpegTran;

		return false;
	}

	private function jpegTran($source_path, $destination_url){
		$command = "jpegtran -copy none -progressive -optimize $source_path > $destination_url";
		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}

	private function jpegOptim($source_path, $destination_url){
		$command = "jpegoptim --strip-all --all-progressive $source_path --dest=$destination_url";

		$output = null;
		
		$response = system($command, $output);

		if( file_exists($destination_url) )
			return $destination_url;
		return false;
	}
}