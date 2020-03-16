<?php
require_once __DIR__.'/../../config/autoload.php';
// echo 'o';
$subscriptions = (new User(1))->getSubscriptions();
$json_string = '[';
foreach ($subscriptions as $sub) {
    $json_string .= '{"id":"'. $sub->id() .'", "name":"'. $sub->rssGetTitle() .'"},';
}
$json_string = substr_replace($json_string, ']', -1);
echo $json_string;