<?php 
    namespace App;
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    use App\lib\Helper;
    use App\Auth\Auth;

    class IndexController{
        function __construct()
        {
            include "conf.php";
            include "views/partials/_header.php"; 

            $auth = new Auth();
            if( isset($_SESSION['user']) && $_SESSION['user'] != null ){
                include "views/main.php";
            }else{
                if( isset($_POST['form_login_submit']) ){
                    if( $auth->authenticate($_POST['email'], $_POST['password']) ){
                        Helper::refreshPage();
                    }else{
                        $_SESSION['auth_errors'] = $auth->errors;

                        include "views/login.php";
                    }
                }else{
                    include "views/login.php";
                }
            }

            if( isset($_SESSION['auth_errors']) ) unset($_SESSION['auth_errors']);

            echo '<br/><br/>';
            include "views/partials/_footer.php";
        }
    }

    new IndexController();
