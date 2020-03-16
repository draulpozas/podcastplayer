<?php
require_once __DIR__.'/../config/autoload.php';

class Database{
    //connection property
    private static $connection;

    /**
    * Connection method. It will connect to the database and assign the new PDO object to the $connection property.
    */
    private static function connect(){
        try{
            $conn_data = json_decode(file_get_contents(__DIR__."/../config/connection.json"), true);
            self::$connection = new PDO($conn_data['CONN_STRING'], $conn_data['DB_USER'], $conn_data['DB_PASS']);
		} catch (PDOException $e){
			echo "Database error: ".$e->getMessage();
			die();
		}
    }

    /**
    * Query method. It will use the $connection property to communicate directly with the database.
    * It receives the .sql path and a replace array for replacing the correspondent values in the predefined query string.
    * It also allows to specify if the replace array fields should be sanitized or not.
    */
    private static function query($file, $replace, $sanitize = false){
		if (!self::$connection) {
			self::connect();
        }
        
        if ($sanitize) {
            $replace = self::sanitizeReplace($replace);
        }

        $query = file_get_contents(__DIR__."/sql/$file");
        $query = strtr($query, $replace);
        $stm = self::$connection->prepare($query);
		$stm->execute();
		return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * Basic sanitazing method. Escapes quotes and double quotes.
    */
    private static function sanitizeReplace($replace){
        $final = [];
        
        foreach ($replace as $field => $value) {
            $value = str_replace('\'', '\\\'', $value);
            $value = str_replace('\"', '\\\"', $value);
            $value = str_replace('<', '&lt;', $value);
            $value = str_replace('>', '&gt;', $value);
            $final[$field] = $value;
        }
        return $final;
    }

    // static methods --------------------
    # user
    public static function insertUser($params) {
        $file = 'insertUser.sql';
        $replace = [
            '{{username}}' => $params['username'],
            '{{passwd}}' => $params['passwd'],
            '{{email}}' => $params['email'],
        ];

        return self::query($file, $replace, true);
    }

    public static function selectUser($where = ''){
        $file = 'selectUser.sql';
        $replace = [
            '{{where}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function updateUser($params, $id){
        $file = 'updateUser.sql';
        $replace = [
            '{{username}}' => $params['username'],
            '{{passwd}}' => $params['passwd'],
            '{{email}}' => $params['email'],
            '{{id}}' => $id,
        ];

        return self::query($file, $replace, true);
    }

    public static function deleteUser($id){
        $file = 'deleteUser.sql';
        $replace = [
            '{{id}}' => $id,
        ];

        return self::query($file, $replace);
    }

    # subscription
    public static function insertSubscription($params) {
        $file = 'insertSubscription.sql';
        $replace = [
            '{{user_id}}' => $params['user_id'],
            '{{name}}' => $params['name'],
            '{{feed}}' => $params['feed'],
        ];

        return self::query($file, $replace, true);
    }

    public static function selectSubscription($where = ''){
        $file = 'selectSubscription.sql';
        $replace = [
            '{{where}}' => $where,
        ];

        return self::query($file, $replace);
    }

    public static function updateSubscription($params, $id){
        $file = 'updateSubscription.sql';
        $replace = [
            '{{user_id}}' => $params['user_id'],
            '{{name}}' => $params['name'],
            '{{feed}}' => $params['feed'],
            '{{id}}' => $id,
        ];

        return self::query($file, $replace, true);
    }

    public static function deleteSubscription($id){
        $file = 'deleteSubscription.sql';
        $replace = [
            '{{id}}' => $id,
        ];

        return self::query($file, $replace);
    }

}

 ?>