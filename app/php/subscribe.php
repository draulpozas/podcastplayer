<?php
require_once __DIR__.'/../../config/autoload.php';
session_start();

$link = $_GET['link'];
$user = $_SESSION['user'];

$user->addSubscription($link);
echo '1';