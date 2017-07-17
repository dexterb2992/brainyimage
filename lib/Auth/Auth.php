<?php 
namespace App\Auth;

use App\Auth\Connection;

class Auth{

	function __construct()
	{
		$conn = new Connection( env('DB_HOST'), env('DB_NAME'), env('DB_USER'), env('DB_PASSWORD') );
		$this->conn = $conn->getConnection();
		$this->user = null;
		$this->errors = array();
	}

	public function check(){
		if( $this->user !== null )
			return true;
		return false;
	}

	public function validate($email, $password){
		$email_errors = array();
		$pass_errors = array();

		if( $email == "" ) $email_errors[] = "The email field is required.";

		if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) $email_errors[] = "The email you entered is not a valid email address.";

		if( count($email_errors) > 0 ) $this->errors["email"] = $email_errors;
		if( count($email_errors) == 0 )
			return true;
		return false;
	}

	public function authenticate($email, $password){
		// Perform queries 
		$email = $this->clean_str($email);
		$password = $this->clean_str($password);

		if( $this->validate($email, $password) ){
			$sql="SELECT * FROM users WHERE email='$email' AND deleted_at IS NULL LIMIT 1";

			if ($result = mysqli_query($this->conn, $sql)){
				$user = $result->fetch_assoc();
				if( password_verify($password, $user['password'])){
					$this->user = $user;
					unset($this->user['password']);
					$_SESSION['user'] = $this->user;
					return true;
				}else{
					$this->errors["login"] = "The password that you've entered is incorrect.";
				}
			}else{
				$this->errors["login"] = "The email or password does not match our records.";
			}
		}

		$_SESSION['auth_errors'] = $this->errors;

		return false;

	}

	public function destroy(){
		session_destroy();
		$this->user = null;
		return true;
	}

	public function clean_str($string){
		return mysqli_escape_string($this->conn, trim($string));
	}

	public function bcrypt($string){
		$options = [
		    'cost' => 12,
		    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
		];

		return password_hash($string, PASSWORD_BCRYPT, $options);
	}

}