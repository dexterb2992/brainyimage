<?php
namespace App\lib;

class Helper{

	public static function formatSizeUnits($bytes){
	    if ($bytes >= 1073741824){
	        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
	    }elseif ($bytes >= 1048576){
	        $bytes = number_format($bytes / 1048576, 2) . ' MB';
	    }elseif ($bytes >= 1024){
	        $bytes = number_format($bytes / 1024, 2) . ' KB';
	    }elseif ($bytes > 1){
	        $bytes = $bytes . ' bytes';
	    }elseif ($bytes == 1){
	        $bytes = $bytes . ' byte';
	    }else{
	        $bytes = '0 bytes';
	    }

	    return $bytes;
	}

	public static function getPNGQuantPath(){
		$path = __DIR__."\pngquant\pngquant.exe";
		return	 $escaped = preg_replace('/\\\\/','\\\\\\\\',$path);
	}

	// When the directory is not empty:
	public static function rrmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir") self::rrmdir($dir."/".$object); 
					else unlink($dir."/".$object);
				}
			}
			reset($objects);
			rmdir($dir);
		}
	}

	public static function pre($str){
		echo '<pre>'; print_r($str); echo '</pre>';
	}

}