<?php
namespace App;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}	

use App\lib\Helper;
use App\Auth\Auth;

class ProfileController{
	function __construct(){
		include "conf.php";
		include "views/partials/_header.php"; 

		$auth = new Auth();
		$this->auth = $auth;

		if( isset($_SESSION['user']) && $_SESSION['user'] != null ){
			if( isset($_POST['q']) ){
				$inputs = $_POST;
				$res = $this->updateUser($inputs, $inputs['q']);
				var_dump($inputs);
			}

			

		    include "views/profile.php";
		}

		echo '<br/><br/>';
		if( isset($_SESSION['_errors']) ) unset($_SESSION['_errors']);

		include "views/partials/_footer.php"; 
	}

	function updateUser($inputs, $update_type){
		// cleans input to prevent SQL Injection
		foreach ($inputs as $key => $input){
			$inputs[$key] = mysqli_escape_string($this->auth->conn, trim($input));
		}
		$sql = "";
		switch ($update_type) {
			case 'update_password':
				if( $this->validate( $inputs, 'update_password' ) ){
					$pass = Helper::bcrypt($inputs['password']);
					$sql = "UPDATE users SET password='$pass' WHERE id=".$this->auth->user['id'];
				}
				break;
			
			case 'general':
				if( $this->validate( $inputs, 'general' ) ){
					$pass = Helper::bcrypt($inputs['password']);
					$sql = "UPDATE users SET name='".$inputs['name']."', access_key='".$inputs['access_key']."'
						 WHERE id=".$this->auth->user['id'];
				}
				break;
		}

		if( isset($sql) ){
			$res = mysqli_query($this->auth->conn, $sql);
		}else{
			$_SESSION['_errors']['profile'] = "Sorry, we can't save your changes right now. Please try again later.";
		}

		return false;
	}

	function validate($inputs, $post_type){
		$errors = array();
		foreach ($inputs as $key => $value):
			if( $inputs[$key] == "" ) $errors[$key][] = "This $key field is required."; 
		endforeach;

		
		switch ($post_type) {
			case 'update_password':
				// confirm old password
				if( !password_verify($inputs['old_password'], $this->auth->user['password']) ){
					$errors['old_password'][] = "Your old password is incorrect.";
				}

				// password
				if( strlen($inputs['password']) < 6 ) $errors['password'][] = "The password should be atleast 6 characters.";
				// password confirmation
				if( $inputs['password_confirmation'] != $inputs['password'] ) $errors['password_confirmation'][] = "Your passwords do not match.";
				break;
			
			case 'general':
				if( !$this->checkLicenseKey($inputs['access_key'], $inputs['email']) ) $errors['access_key'][] = "Sorry, we can't find the license and email that you entered on our records.";
				break;

		}		

		if( count($errors) > 0 ){
			$_SESSION['_errors'] = $errors;
			return false;
		}

		return true;
	}

	function checkLicenseKey($key, $email){
		return true;
	}
}

new ProfileController();