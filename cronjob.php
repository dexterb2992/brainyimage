<?php 

class CronJob{
	function __construct()
	{
		$this->dir = __DIR__.'/uploads/';

		// delete from output folder
		$this->deleteOldFiles( glob($this->dir."output/*") );
		// delete from source folder
		$this->deleteOldFiles( glob($this->dir."source/*") );
	}

	private function deleteOldFiles($dir){
		foreach($dir as $key => $file) {
			$this->pre("$key: ".$file);
			// current time minus 60 seconds * 60 (minutes) * 24 (hours) * 2 (days).
		    if (filemtime($file) < time() - (60 * 60 * 24 * 1) ) {
		        if (is_dir($file)){
		        	rmdir($file);
		        }elseif (is_file($file)){
		        	unlink($file);
		        }
		    }
		}
	}

	public function pre($str){
		echo '<pre>'; print_r($str); echo '</pre>';
	}
}

new CronJob();