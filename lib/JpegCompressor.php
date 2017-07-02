<?php
namespace App\lib;

class JpegCompressor{
	function __construct($source_path, $destination_path)
	{
		$this->source_path = $source_path;
		$this->destination_path = $destination_path;
	}

	public function optimize(){
		$command = "jpegtran -copy none -progressive -optimize ".escapeshellarg($this->source_path)." > ".escapeshellarg($this->destination_path);

	}
}