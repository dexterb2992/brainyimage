<?php 
require "vendor/autoload.php";

define('APP_NAME', 'Brainy Image');
define('APP_DESCRIPTION', 'Optimize your images with a perfect balance in quality and file size.');
define('APP_KEYWORDS', 'image optimizer, compress image, reduce image filesize');
define('APP_AUTHOR', 'TopDogIMSolutions');
define('APP_FOOTER', 'TopDogIMSolutions.com');
define('APP_FOOTER_LINK', 'http://topdogimsolutions.com');
define('LICENSE_LINK', 'http://topdogimsolutions.com/licensing/index.php');

function env($key){
	// live
	$live = array(
		'pngquant' => '/usr/local/bin/pngquant',
		'optipng' => '/usr/local/bin/optipng',
		'pngcrush' => '/usr/bin/pngcrush',
		'pngout' => '/usr/bin/pngout',
		'jpegtran' => '/usr/bin/jpegtran',
		'jpegoptim' => '/usr/local/bin/jpegoptim',
		'DB_NAME' => 'imdog_brainyimage',
		'DB_HOST' => 'localhost',
		'DB_USER' => 'imdog_dexter',
		'DB_PASSWORD' => 'K{Cb~5_p#P)['
	);

	$local = array(
		'pngquant' => "E:\\wamp64\\www\\brainyimage\\pngquant\\pngquant.exe",
		'DB_NAME' => 'brainyimage',
		'DB_HOST' => 'localhost',
		'DB_USER' => 'root',
		'DB_PASSWORD' => ''
	);

	if( $_SERVER['HTTP_HOST'] != "localhost" ){
		return $key == "" ? $live : $live[$key];
	}

	return $key == "" ? $local : $local[$key];
}