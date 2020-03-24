<?php
require_once __DIR__."/../config/autoload.php";

class AppController {
    public static function player() {
        echo file_get_contents('../app/player.html');
    }
}


 ?>