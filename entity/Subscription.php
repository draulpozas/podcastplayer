<?php
require_once __DIR__.'/../config/autoload.php';

class Subscription {
    // Class properties
    private $id;
    private $user_id;
    private $feed;
    private $rssDoc = null;

    // Constructor
    public function __construct($id = null) {
        if ($id) {
            $data = Database::selectSubscription("WHERE id = $id");
            if (!$data) {
                return false;
            }
            $params = $data[0];
            $this->id = $id;
            $this->user_id = $params['user_id'];
            $this->feed = $params['feed'];
        }
    }

    // get-set methods
    public function id() {
        return $this->id;
    }

    public function user_id($user_id = null) {
        if ($user_id) {
            $this->user_id = $user_id;
        }
        return $this->user_id;
    }

    public function feed($feed = null) {
        if ($feed) {
            $this->feed = $feed;
        }
        return $this->feed;
    }

    //basic methods
    /**
    * Save method. Collects the values of the object's properties, then it uses the Database class to save the information in the database.
    * It inserts or updates depending on whether or not an id has been set. This is because the id is only generated when the row is inserted in the database;
    * thus, if id is not defined, the object is considered a new row yet to be inserted in the database; if it has an id, it represents data loaded from a database record.
    */
    public function save(){
        $params = [
            'user_id' => $this->user_id(),
            'feed' => $this->feed(),
        ];

        if ($this->id()) {
            return Database::updateSubscription($params, $this->id());
        } else {
            return Database::insertSubscription($params);
        }
    }

    /**
    * Deletes the row from the database table "subscription" where the id is equal to the id of the current object.
    */
    public function delete(){
        Database::deleteSubscription($this->id());
    }

    // Other methods
    public function getEpisodes($n = null) {
        $episodes = [];

        if ($n) {
            for ($i=0; $i < $n; $i++) { 
                $epi = new Episode($this->rssGetItem($i));
                array_push($episodes, $epi);
            }
        } else {
            $items = $this->rssGetItems();
            foreach ($items as $item) {
                array_push($episodes, new Episode($item));
            }
        }

        return $episodes;
    }

    // RSS handling methods
    private function loadRss() {
        $this->rssDoc = new DOMDocument();
        $this->rssDoc->load($this->feed());
        // return $this->rssDoc;
    }

    private function channel() {
        if (!$this->rssDoc) {
            $this->loadRss();
        }
        return $this->rssDoc->getElementsByTagName('channel')->item(0);
    }

    public function rssGetTitle() {
        return $this->channel()->getElementsByTagName('title')->item(0)->nodeValue;
    }

    public function rssGetLink() {
        return $this->channel()->getElementsByTagName('link')->item(0)->nodeValue;
    }

    public function rssGetLanguage() {
        return $this->channel()->getElementsByTagName('language')->item(0)->nodeValue;
    }

    public function rssGetAuthor() {
        return $this->channel()->getElementsByTagName('author')->item(0)->nodeValue;
    }

    public function rssGetOwnerName() {
        return $this->channel()->getElementsByTagName('owner')->item(0)->getElementsByTagName('name')->item(0)->nodeValue;
    }
    
    public function rssGetOwnerEmail() {
        return $this->channel()->getElementsByTagName('owner')->item(0)->getElementsByTagName('email')->item(0)->nodeValue;
    }

    public function rssGetItems() {
        return $this->channel()->getElementsByTagName('item');
    }

    public function rssGetItem($n) {
        return $this->rssGetItems()->item($n);
    }

    public function rssGetEnclosure($n) {
        return $this->channel()->getElementsByTagName('enclosure')->item(0)->attributes->item(0)->nodeValue;
    }
}

 ?>