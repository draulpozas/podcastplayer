<?php
require_once __DIR__.'/../config/autoload.php';

class User {
    // Class properties
    private $id;
    private $username;
    private $passwd;
    private $email;

    // Constructor
    public function __construct($id = null){
        if ($id) {
            $data = Database::selectUser("WHERE id = $id");
            if (!$data) {
                return false;
            }
            $params = $data[0];
            $this->id = $id;
            $this->username = $params['username'];
            $this->passwd = $params['passwd'];
            $this->email = $params['email'];
        }
    }

    // get-set methods
    public function id(){
        return $this->id;
    }

    public function username($username = null){
        if ($username){
            $this->username = $username;
        }
        return $this->username;
    }

    public function passwd($passwd = null){
        if ($passwd){
            $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
        }
        return $this->passwd;
    }

    public function email($email = null){
        if ($email){
            $this->email = $email;
        }
        return $this->email;
    }

    //basic methods
    /**
    * Save method. Collects the values of the object's properties, then it uses the Database class to save the information in the database.
    * It inserts or updates depending on whether or not an id has been set. This is because the id is only generated when the row is inserted in the database;
    * thus, if id is not defined, the object is considered a new row yet to be inserted in the database; if it has an id, it represents data loaded from a database record.
    */
    public function save(){
        $params = [
            'username' => $this->username(),
            'passwd' => $this->passwd(),
            'email' => $this->email(),
        ];

        if ($this->id()) {
            return Database::updateUser($params, $this->id());
        } else {
            return Database::insertUser($params);
        }
    }

    /**
    * Deletes the row from the database table "user" where the id is equal to the id of the current object.
    */
    public function delete(){
        Database::deleteUser($this->id());
    }

    // other methods
    public function getSubscriptions() {
        $data = Database::selectSubscription("WHERE user_id = ". $this->id());

        $subscriptions = [];
        foreach ($data as $row) {
            array_push($subscriptions, new Subscription($row['id']));
        }

        return $subscriptions;
    }

    public function addSubscription($subFeed) {
        $sub = new Subscription();
        $sub->user_id($this->id());
        $sub->feed($subFeed);
        return $sub->save();
        // $params = [
        //     'user_id' => $this->id(),
        //     'feed' => $subFeed,
        // ];

        // return Database::insertSubscription($params);
    }

    // public function editSubscription($subFeed, $id) {
    //     $params = [
    //         'user_id' => $this->id(),
    //         'feed' => $subFeed,
    //     ];

    //     return Database::updateSubscription($params, $id);
    // }

    // /**
    //  * Returns whether or not the credentials given as parameters are valid for logging the current user in.
    //  */
    // public function verify($username, $passwd) {
    //     return (password_verify($passwd, $this->passwd()) && ($username == $this->username() || $username == $this->email()));
    // }

    /**
     * Returns a User object where the username is equal to the one given as a parameter.
     * If no user is found for the provided username, it returns false.
     */
    public static function getByUsername($username){
        $data = Database::selectUser("WHERE username = '$username'");
        if ($data) {
            $usr = new User($data[0]['id']);
            return $usr;
        }
        return false;
    }

    /**
     * Returns a User object where the email address is equal to the one given as a parameter.
     * If no user is found for the email address, it returns false.
     */
    public static function getByEmail($email){
        $data = Database::selectUser("WHERE email = '$email'");
        if ($data) {
            $usr = new User($data[0]['id']);
            return $usr;
        }
        return false;
    }

    /**
     * Checks whether or not the credentials given as a parameter are valid for any existing user.
     * If they are, it returns that user's id. If they are not, it returns false.
     */
    public static function checkLogin($username, $passwd){
        $usr = User::getByUsername($username);
        if (!$usr) {
            $usr = User::getByEmail($username);
        }
        if (!$usr) {
            return false;
        }
        if (password_verify($passwd, $usr->passwd())) {
            return $usr;
        }
        // if ($usr->verify($username, $passwd)) {
        //     return $usr->id();
        // }
    }

}

 ?>