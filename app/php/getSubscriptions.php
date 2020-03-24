<?php
require_once __DIR__.'/../../config/autoload.php';
session_start();
$user = $_SESSION['user'];
$subscriptions = ($user->getSubscriptions());
$json_string = '[';
foreach ($subscriptions as $sub) {
    $json_string .= '{"id":"'. $sub->id() .'", "name":"'. $sub->rssGetTitle() .'"},';
}
$json_string = substr_replace($json_string, ']', -1);
echo $json_string;