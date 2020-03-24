<?php
require_once __DIR__."/../config/autoload.php";
session_start();

if (!isset($_SESSION['user'])) {
    LoginController::main();
} else {
    if (!$_SESSION['user']) {
        LoginController::main();
    } else {
        AppController::player();
    }
}

 ?>