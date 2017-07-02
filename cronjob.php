<?php 
namespace App;  

require_once "conf.php";

use App\lib\Helper;

class CronJob{
	function __construct()
	{
		$this->dir = __DIR__.'/uploads/';
		$this->deleted_count = 0;

		// delete from output folder
		$this->deleteOldFiles( glob($this->dir."output/*") );
		// delete from source folder
		$this->deleteOldFiles( glob($this->dir."source/*") );


	}

	private function deleteOldFiles($dir){
		foreach($dir as $key => $file) {
			$str = "$key: ".$file;
			// current time minus 60 seconds * 60 (minutes) * 24 (hours) * 2 (days).
			$current_time = time() - (60 * 60 * 24 * 2);
		    if (filemtime($file) < $current_time ) {
		        if (is_dir($file)){
		        	Helper::rrmdir($file);
		        }elseif (is_file($file)){
		        	unlink($file);
		        }
		        $str.= "(success: filetime: ".filemtime($file).", current_time: $current_time)";
		        $this->deleted_count++;
		    }else{
		    	$str.= "(failed: filetime: ".filemtime($file).", current_time: $current_time)";
		    }

		    Helper::pre($str);
		}

		echo '<br/><strong>Deleted a total of '.$this->deleted_count.' file/s.</strong><br/>';
	}

	
}

new CronJob();