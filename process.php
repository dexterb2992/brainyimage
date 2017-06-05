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

					$this->compress($_POST['src'], $_POST['filename']);
					break;
			}
		}

		
		
	}

	public function upload($files){
		if( isset($files['img_file']) ){
			$response = $this->brainyImage->upload($files);
		}else{
			$response = array("error" => true, "msg" => "No image received.");
		}

		echo json_encode($response);
	}

	public function compress($src, $filename = ""){
		$res = $this->brainyImage->compressUploaded($src, $filename);

		echo json_encode($res);
	}
}

new AjaxProcessImage();