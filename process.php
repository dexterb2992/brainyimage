<?php
namespace App;

require_once "conf.php";

use \App\lib\BrainyImage;

class AjaxProcessImage{

	function __construct()
	{
		$this->brainyImage = new BrainyImage();

		if( isset($_POST['q']) ){
			switch ($_POST['q']) {
				case 'upload':
					$this->upload($_FILES);
					break;
				
				case 'compress':
					$filename = $_POST['filename'];
					if( $filename == '**from_url**' ){
						$filename = basename($_POST['src']);
					}

					$this->compress($_POST['src'], $filename);
					break;

				case 'retry':
					$this->retry($_POST['src'], basename($_POST['src']));
					break;
			}
		}
	}

	private function upload($files){
		if( isset($files['img_file']) ){
			$response = $this->brainyImage->upload($files);
		}else{
			$response = array("error" => true, "msg" => "No image received.");
		}

		echo json_encode($response);
	}

	private function compress($src, $filename = ""){
		$res = $this->brainyImage->compressUploaded($src, $filename);

		echo is_array($res) ? json_encode($res) : $res;
	}

	private function retry($src, $filename = ""){
		return $this->compress($src, $filename);
	}
}

new AjaxProcessImage();