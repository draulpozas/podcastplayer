<?php
require_once __DIR__.'/../../config/autoload.php';

$link = $_GET['link'];
$user = new User(1);

$user->addSubscription($link);
echo '1';