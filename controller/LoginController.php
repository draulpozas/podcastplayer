<?php
require_once __DIR__."/../config/autoload.php";

class LoginController {
    public static function main(){
        if ($_POST) {
            $_SESSION['user'] = User::checkLogin($_POST['username'], $_POST['passwd']);
            header("Location: ../app/index.php");
        }
        echo file_get_contents(__DIR__.'/../app/login.html');
    }
}

 ?>