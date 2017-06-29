<?php
namespace App;

include "conf.php";

// use \App\lib\PNGQuant;

use \PHPImageOptim\PHPImageOptim;

use \PHPImageOptim\Tools\Png\AdvPng;
use \PHPImageOptim\Tools\Png\OptiPng;
use \PHPImageOptim\Tools\Png\PngOut;
use \PHPImageOptim\Tools\Png\PngCrush;
use \PHPImageOptim\Tools\Png\PngQuant;
use \PHPImageOptim\Tools\Jpeg\JpegTran;
use \PHPImageOptim\Tools\Jpeg\JpegOptim;

class Test{
	function __construct()
	{

		$this->optiPng = new OptiPng();
		$this->optiPng->setBinaryPath(env('optipng'));

		$this->pngOut = new PngOut();
		$this->pngOut->setBinaryPath(env('pngout'));

		$this->pngCrush = new PngCrush();
		$this->pngCrush->setBinaryPath(env('pngcrush'));

		$this->pngQuant = new PngQuant();
		$this->pngQuant->setBinaryPath(env('pngquant'));

		$this->jpegTran = new JpegTran();
		$this->jpegTran->setBinaryPath(env('jpegtran'));

		$this->jpegOptim = new JpegOptim();
		$this->jpegOptim->setBinaryPath(env('jpegoptim'));

		$this->optimizePNG();
		$this->optimizeJPEG();
	}

	public function optimizePNG(){
		$optim = new PHPImageOptim();
		echo __DIR__.'/tests/images/lenna-original.png';
		$optim->setImage(__DIR__.'/tests/images/lenna-original.png');

		$optim
			// ->chainCommand($pngQuant)
		    // ->chainCommand($advPng)
		    // ->chainCommand($optiPng)
		    ->chainCommand($this->pngCrush);
		    // ->chainCommand($pngOut);
		$optim->optimise();
	}

	public function optimizeJPEG(){
		$optim = new PHPImageOptim();
		echo __DIR__.'/tests/images/mountmckinley_ba.jpg';
		$optim->setImage(__DIR__.'/tests/images/mountmckinley_ba.jpg');

		$optim->chainCommand($this->jpegTran);
		$optim->optimise();
	}
}

new Test();