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

	public static function log($str){

	}

	/** 
	 * Identifies which file is the smallest
	 * @param array $paths
	 * @return string <path> of smallest in size
	 */
	public static function identifyLesserSize($paths){
		$result = false;
		$lowest = 0;

		foreach ($paths as $key => $path) {
			$size = filesize($path);

			if( $key == 0 ) $lowest = $size;

			if( $lowest < $size ){
				continue;
			}else{
				$lowest = $size;
				$result = $path;
			}
		}
		
		return $result;
	}

	// redirect to spefic url
	public static function redirect($url){
		echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
	}

	public static function refreshPage(){
		echo '<meta http-equiv="refresh" content="0">';
	}

	public static function url($path){
		$scheme = $_SERVER['REQUEST_SCHEME'];
		$host = $_SERVER['HTTP_HOST'];
		return $scheme."://$host/$path";
	}

	public static function showErrors($errors, $custom_class = 'danger'){
		if( is_array($errors) ){
			foreach ($errors as $key => $error) {
				echo '<div class="alert alert-'.$custom_class.'">'.$error.'</div>';
			}
		}else if( is_string($errors) ){
			echo '<div class="alert alert-'.$custom_class.'">'.$errors.'</div>';
		}
	}


	public static function bcrypt($string){
		$options = [
		    'cost' => 12,
		    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
		];

		return password_hash($string, PASSWORD_BCRYPT, $options);
	}

	public static function upload($file, $target_dir){
		$filename = preg_replace( '/[^a-z0-9.]+/', '_', strtolower( $file['name'] ) );

		$file_size =$file['size'];
		$file_tmp =$file['tmp_name'];
		$file_type=$file['type'];

		$extensions = array("image/jpeg","image/png");

		$var = explode('.',$filename);
		$file_ext = strtolower($var[1]);

		$target = $target_dir.$filename;

		$results = array();
		$error = null;

		if(in_array($file_type, $extensions ) === true)
		{
			if(move_uploaded_file($file_tmp, $target))
			{
				
				$results = array(
					"success" => 1, 
					"url" => $target,
					"filename" => $filename
				);
			}else
				$error = 'Error in uploading few files. Some files couldn\'t be uploaded.';				
		}else{
			$error = 'Error in uploading few files. File type is not allowed.';
		}
		$results['info'] = $file_tmp;
		$results['filetype'] = $file_type;
		

		if( $error != "" ){
			$results["error"] = $error;
			$results['success'] = 0;
			
		}
		

		return $results;	
	}

	public static function checkLicenseKey($key, $email){
		// return true;
		$url = LICENSE_LINK."?licensekey=$key&email=$email&pl_type=brainyimage&domainname=".$_SERVER['HTTP_HOST'];
		$data = file_get_contents($url);
		if( $data != '' ){
			$res = json_decode($data);
			if( $res !== false && $res->valid == 1 )
				return true;
		}

		return false;
	}

}