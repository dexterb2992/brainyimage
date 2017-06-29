<?php
namespace App;

include "conf.php";

use use App\lib\PNGQuant;

use \PHPImageOptim\PHPImageOptim;

use \PHPImageOptim\Tools\Png\AdvPng;
use \PHPImageOptim\Tools\Png\OptiPng;
use \PHPImageOptim\Tools\Png\PngOut;
use \PHPImageOptim\Tools\Png\PngCrush;
use \PHPImageOptim\Tools\Png\PngQuant;
use \PHPImageOptim\Tools\Png\JpegTran;
use \PHPImageOptim\Tools\Png\JpegOptim;

class Test{
	function __construct()
	{
		$this->advPng = new AdvPng();
		$this->advPng->setBinaryPath(env('advpng'));

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

		
	}

	public function optimizePNG(){
		$optim = new PHPImageOptim();
		$optim->setImage('tests/images/lenna-original.png');

		$optim
			// ->chainCommand($pngQuant)
		    // ->chainCommand($advPng)
		    // ->chainCommand($optiPng)
		    ->chainCommand($this->pngCrush)
		    // ->chainCommand($pngOut);
		$optim->optimise();
	}

	public function optimizeJPEG(){
		$optim = new PHPImageOptim();
		$optim->setImage('tests/images/mountmckinley_ba.jpg');

		$optim->chainCommand($this->jpegTran);
		$optim->optimise();
	}
}

new Test();