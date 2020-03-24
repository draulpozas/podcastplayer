<?php
require_once __DIR__."/../config/autoload.php";

class AppController {
    public static function player() {
        echo file_get_contents('../app/player.html');
    }

    public static function signUp() {
        if ($_POST) {
            $usr = new User();
            $usr->username($_POST['username']);
            $usr->email($_POST['email']);
            $usr->passwd($_POST['passwd']);
            $usr->save();
            LoginController::main();
        } else {
            echo file_get_contents(__DIR__.'/../app/signup.html');
        }
    }
}


 ?>