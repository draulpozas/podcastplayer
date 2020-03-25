<?php
require_once __DIR__.'/../../config/autoload.php';
session_start();

$link = $_GET['link'];
$user = $_SESSION['user'];

if ($user->addSubscription($link)) {
    echo '1';
} else {
    echo '0';
}