<?php
require_once __DIR__.'/../../config/autoload.php';

$sub = new Subscription($_GET['subscription']);
$episodes = $sub->getEpisodes();

$json_string = '[';
foreach ($episodes as $epi) {
    $json_string .= '{"title":"'. $epi->title .'", "src":"'. $epi->srcAudio .'"},';
}
$json_string = substr_replace($json_string, ']', -1);
echo $json_string;