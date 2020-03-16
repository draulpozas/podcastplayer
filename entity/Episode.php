<?php
require_once __DIR__.'/../config/autoload.php';

class Episode {
    // class properties
    public $title;
    // public $srcImg;
    public $srcAudio;
    // public $episode;
    // public $link;

    // constructor
    public function __construct($item) {
        $this->title = $item->getElementsByTagName('title')->item(0)->nodeValue;
        // $this->srcImg = $item->getElementsByTagName('image')->item(0)->attributes->item(0)->nodeValue;
        $this->srcAudio = $item->getElementsByTagName('enclosure')->item(0)->attributes->item(0)->nodeValue;
        // $this->episode = $item->getElementsByTagName('episode')->item(0)->nodeValue;
        // $this->link = $item->getElementsByTagName('link')->item(0)->nodeValue;
    }
}

 ?>