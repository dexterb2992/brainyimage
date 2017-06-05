<?php
namespace App\lib;

use App\lib\traits\ImageCompressor;
use App\lib\Helper;

class BrainyImage {
	use ImageCompressor;

	function __construct($dir = 'uploads/source/', $extensions = array("jpeg","jpg","png"))
	{
		$this->error = '';
		$this->img = '';
		$this->dir = $dir;
		$this->extensions = $extensions;

		$this->filename = "";
		$this->result = array();
	}

	public function upload($file){
		foreach($file['img_file']['tmp_name'] as $key => $tmp_name ){
			$filename = $file['img_file']['name'][$key];
			$file_size =$file['img_file']['size'][$key];
			$file_tmp =$file['img_file']['tmp_name'][$key];
			$file_type=$file['img_file']['type'][$key];

			$var = explode('.',$filename);
			$file_ext = strtolower($var[1]);

			$target = $this->dir.$filename;

			$results = array();


			if(in_array($file_ext,$this->extensions ) === true)
			{
				if(move_uploaded_file($file_tmp, $this->dir.$filename))
				{
					
					$results = array(
						"success" => 1, 
						"url" => $this->dir.$filename,
						"filename" => $filename
					);
				}else
					$this->error = 'Error in uploading few files. Some files couldn\'t be uploaded.';				
			}else{
				$this->error = 'Error in uploading few files. File type is not allowed.';
			}
			$results['info'] = $tmp_name;
		}

		if( $this->error != "" ){
			$results["error"] = $this->error;
			$results['success'] = 0;
			
		}
		

		return $results;	
	}

	public function compressUploaded($source_photo, $filename){
		$dest_photo = 'uploads/output/'.$filename;
		 
		$info = getimagesize($source_photo);

		if ($info['mime'] == 'image/jpeg'){
			// $dest_photo.= '.jpg';
			$d = $this->compressJPEG($source_photo, $dest_photo, 90);
		}else if($info['mime'] == 'image/png'){
			// $dest_photo.= '.png';
			$dest_photo = 'uploads/output/'.time().".png";
			$d = $this->compressPNG($source_photo, $dest_photo, 90);
		}


		$old_size = filesize($source_photo);
		$new_size = filesize($dest_photo);
		$size_difference = $old_size - $new_size;

		$percentage_saved = (($size_difference)/$old_size)*100;

		if( $new_size > $old_size ){
			$new_size = $old_size;
			$dest_photo = $source_photo;
			$percentage_saved = 0;
		}

		$results = array(
			"success" => 1,
			"input" => array(
				"size" => Helper::formatSizeUnits($old_size),
				"filename" => $this->filename,
				"type" => $info['mime']
			),
			"output" => array(
				"size" => Helper::formatSizeUnits($new_size),
				"type" => $info['mime'],
				"url" => $dest_photo,
				"diff" => round($percentage_saved, 2).'%'
			)
		);

		return $results;
	}
}