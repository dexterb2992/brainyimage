<?php
namespace App;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}	

use App\lib\Helper;
use App\Auth\Auth;


class RegisterController{
	function __construct()
	{
		include "conf.php";
		include "views/partials/_header.php"; 

		$auth = new Auth();
		$this->auth = $auth;

		if( isset($_SESSION['user']) && $_SESSION['user'] != null ){
		    Helper::redirect('./index.php');
		}else{
		    if( isset($_POST['form_register_submit']) ){
		    	$res = $this->register($_POST);

		        if( $res ){
		            echo '<meta http-equiv="refresh" content="0; url=./index.php">';
		        }else{
		            include "views/register.php";
		        }
		    }else{
		        include "views/register.php";
		    }
		}

		if( isset($_SESSION['reg_errors']) ) unset($_SESSION['reg_errors']);
		if( isset($_SESSION['last_inputs']) ) unset($_SESSION['last_inputs']);

		echo '<br/><br/>';

		include "views/partials/_footer.php"; 
	}

	function emailExists($email){
		$sql = "SELECT * FROM users WHERE email='$email'";
		$res = mysqli_query($this->auth->conn, $sql);

		if( $res->num_rows > 0 ) 
			return true;
		return false;
	}

	function register($inputs){
		// cleans input to prevent SQL Injection
		foreach ($inputs as $key => $input):
			$inputs[$key] = mysqli_escape_string($this->auth->conn, trim($input));
		endforeach;

		if( $this->validate( $inputs ) ):
			$name = $inputs['name'];
			$pass = Helper::bcrypt($inputs['password']);
			$email = $inputs['email'];
			$license_key = $inputs['license_key'];
			$sql = "INSERT INTO users (name, email, password, access_key, access_token) VALUES
				('$name', '$email', '$pass', '$license_key', '".md5($license_key)."')";
			if( mysqli_query($this->auth->conn, $sql) )
				if( $this->auth->authenticate($email, $inputs['password']) )
					return true;

		endif;

		return false;
	}

	function validate($inputs){
		$errors = array();
		foreach ($inputs as $key => $value):
			if( $inputs[$key] == "" ) $errors[$key][] = "This $key field is required."; 
		endforeach;

		// email
		if ( !filter_var($inputs['email'], FILTER_VALIDATE_EMAIL) ) $errors['email'][] = "The email you entered is not a valid email address.";

		// check email if already exists
		if( $this->emailExists($inputs['email']) ) $errors['email'][] = "This email is already taken.";

		// password
		if( strlen($inputs['password']) < 6 ) $errors['password'][] = "The password should be atleast 6 characters.";
		// password confirmation
		if( $inputs['password_confirmation'] != $inputs['password'] ) $errors['password_confirmation'][] = "Your passwords do not match.";

		if( !$this->checkLicenseKey($inputs['license_key'], $inputs['email']) ) $errors['license_key'][] = "Sorry, we can't find the license and email that you entered on our records.";

		if( count($errors) > 0 ){
			$_SESSION['reg_errors'] = $errors;
			$_SESSION['last_inputs'] = $inputs;
			return false;
		}

		return true;
	}

	function checkLicenseKey($key, $email){
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

new RegisterController();