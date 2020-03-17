<?php
require_once __DIR__.'/../../config/autoload.php';

$sub = new Subscription($_GET['subscription']);
$episodes = $sub->getEpisodes();

$json_string = '[';
foreach ($episodes as $epi) {
    $title = str_replace('"', '&quot;', $epi->title);
    $title = str_replace('	', '', $title);
    $json_string .= '{"title":"'. $title .'", "src":"'. str_replace('"', '\"', $epi->srcAudio) .'", "podcast":"'. str_replace('"', '&quot;', $sub->rssGetTitle()) .'"},';
}
$json_string = substr_replace($json_string, ']', -1);
echo $json_string;