<?php
namespace App\lib\traits;

use App\lib\Helper;

use App\lib\traits\JpegCompressor;
use App\lib\traits\PngCompressor;

trait ImageCompressor {
	use JpegCompressor;
	use PngCompressor;

	public function compressJPEG($source_path, $destination_dir){
		$info = getimagesize($source_path);
		
		mkdir( $destination_dir."jpegtran");
		mkdir( $destination_dir."jpegoptim");
		$jpegtran_dest = $destination_dir."jpegtran/".basename($source_path);
		$jpegoptim_dir = $destination_dir."jpegoptim/";

		$jpegTran = $this->jpegTran($source_path, $jpegtran_dest);
		$jpegOptim = $this->jpegOptim($source_path, $jpegoptim_dir);

		sleep(2);

		$flag1 = $jpegTran != false && file_exists($jpegTran) ? true: false;
		$flag2 = $jpegOptim != false && file_exists($jpegOptim) ? true: false;

		if( $flag1 && $flag2 )
			return Helper::identifyLesserSize( array($jpegTran, $jpegOptim) );
		
		if( !$flag2 && $flag1 ) return $jpegTran;
		if( !$flag1 && $flag2 ) return $jpegOptim;

		return false;
	}

	public function compressPNG($source_path, $destination_dir){
		
		$filename = basename($source_path);
		mkdir($destination_dir.'pngquant/');
		mkdir($destination_dir.'pngcrush/');

		$pngquant_dest = $destination_dir.'pngquant/'.$filename;
		$pngcrush_dest = $destination_dir.'pngcrush/'.$filename;

		$this->pngQuant($source_path, $pngquant_dest);

		$this->pngCrush($source_path, $pngcrush_dest);
		sleep(2);

		$flag1 = file_exists($pngquant_dest) ? true: false;
		$flag2 = file_exists($pngcrush_dest) ? true: false;

		if( $flag1 && $flag2 ){
			return Helper::identifyLesserSize( array($pngquant_dest, $pngcrush_dest) );
		}

		if( !$flag2 && $flag1 ) return $pngquant_dest;
		if( !$flag1 && $flag2 ) return $pngcrush_dest;

		return false;
	}	

}