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
		// if( file_get_contents("env.php") == "development" ){
		// 	return "E:\\wamp64\\www\\brainyimage\\pngquant\\pngquant.exe";
		// }

		$path = __DIR__."\pngquant\pngquant.exe";
		return	 $escaped = preg_replace('/\\\\/','\\\\\\\\',$path);
	}
}