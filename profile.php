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
				$_SESSION['last_inputs'] = $inputs;
				$res = $this->updateUser($inputs, $inputs['q']);
			}

		    include "views/profile.php";
		}else{
			Helper::redirect('./index.php');
		}

		echo '<br/><br/>';
		if( isset($_SESSION['_errors']) ) unset($_SESSION['_errors']);
		if( isset($_SESSION['last_inputs']) ) unset($_SESSION['last_inputs']);
		if( isset($_SESSION['_flash_message']) ) unset($_SESSION['_flash_message']);

		include "views/partials/_footer.php"; 
	}

	function updateUser($inputs, $update_type){
		// cleans input to prevent SQL Injection
		foreach ($inputs as $key => $input){
			$inputs[$key] = mysqli_escape_string($this->auth->conn, trim($input));
		}
		$sql = "";

		if( $update_type == 'update_password' ){
				if( $this->validate( $inputs, $update_type ) ){
					$pass = Helper::bcrypt($inputs['password']);
					$sql = "UPDATE users SET password='$pass' WHERE id=".$_SESSION['user']['id'];
				}		
		}else if( $update_type == 'general' ){
				if( $this->validate( $inputs, $update_type ) ){
					$sql = "UPDATE users SET name='".$inputs['name']."', access_key='".$inputs['access_key']."'
						 WHERE id=".$_SESSION['user']['id'];
				}
		}else if( $update_type == "avatar" ){
			$upload_res = Helper::upload($_FILES['avatar'], './assets/images/avatar/');
			if( is_array($upload_res) ){
				if( $upload_res['success'] == 1 ){
					$sql = "UPDATE users SET avatar='".$upload_res['url']."' WHERE id=".$_SESSION['user']['id'];
				}else{
					$_SESSION['_errors']['profile'] = "An error has occured while tring to upload your image..";
					return false;
				}
			}
		}

		
		if( $sql != "" ){
			$res = mysqli_query($this->auth->conn, $sql);
			$_SESSION['_flash_message'] = "Your changes have been saved.";
			if( $res ) $this->getUser($_SESSION['user']['id']);
				return true;
			$_SESSION['_errors']['profile'] = "An error has occured while tring to save your changes..";

		}else{
			$_SESSION['_errors']['profile'] = "Sorry, we can't save your changes right now. Please try again later.";
		}


		return false;
	}

	function getUser($id){
		$sql = "SELECT * FROM users WHERE id=$id LIMIT 1";
		if ($result = mysqli_query($this->auth->conn, $sql)){
			$_SESSION['user'] = $result->fetch_assoc();
		} 
	}

	function validate($inputs, $update_type){
		$errors = array();
		foreach ($inputs as $key => $value):
			if( $inputs[$key] == "" ) $errors[$key][] = "This $key field is required."; 
		endforeach;

		
		switch ($update_type) {
			case 'update_password':
				// confirm old password
				if( !password_verify($inputs['old_password'], $_SESSION['user']['password']) ){
					$errors['old_password'][] = "Your old password is incorrect.";
				}

				// password
				if( strlen($inputs['password']) < 6 ) $errors['password'][] = "The password should be atleast 6 characters.";
				// password confirmation
				if( $inputs['password_confirmation'] != $inputs['password'] ) $errors['password_confirmation'][] = "Your passwords do not match.";
				break;
			
			case 'general':
				if( !Helper::checkLicenseKey($inputs['access_key'], $inputs['email']) ) $errors['access_key'][] = "Sorry, we can't find the license and email that you entered on our records.";
				break;

		}		

		if( count($errors) > 0 ){
			$_SESSION['_errors'] = $errors;
			return false;
		}

		return true;
	}
}

new ProfileController();