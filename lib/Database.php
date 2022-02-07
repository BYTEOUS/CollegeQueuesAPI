<?php
class Database {
    public static $db_config = array(
        'connection' => 'mysql',
        'host' => '141.8.192.26',
        'database' => 'a0514069_study',
    
        'username' => 'a0514069_study',
        'password' => 'ccDI0tcg'
    );
    public static $pdo = null;

    public static function connect() {
        $connectString = Database::$db_config['connection'].':host='.Database::$db_config['host'].';dbname='.Database::$db_config['database'];
        
        try {
            Database::$pdo = new PDO($connectString, Database::$db_config['username'], Database::$db_config['password']);
            return true;
        } 
        catch (PDOException $e) {
            return false;
        }
    }
    public static function tryConnect() {
        $connectString = Database::$db_config['connection'].':host='.Database::$db_config['host'].';dbname='.Database::$db_config['database'];
        
        try {
            Database::$pdo = new PDO($connectString, Database::$db_config['username'], Database::$db_config['password']);
            return array(
                'status' => 'success'
            );
        } 
        catch (PDOException $e) {
            return array(
                'status' => 'fail',
                'message' => strval($e)
            );
        }
    }

    public static function execute(string $query, array $vars = []) {
        $stmt = Database::$pdo->prepare($query);
        $stmt->execute($vars);
    }
    public static function executeFetch(string $query, array $vars = []) {
        $stmt = Database::$pdo->prepare($query);
        $stmt->execute($vars);
        return $stmt->fetch();
    }
    public static function executeFetchAll(string $query, array $vars = []) {
        $stmt = Database::$pdo->prepare($query);
        $stmt->execute($vars);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Users
    public static function getUser($login) {
        return Database::executeFetch('SELECT * FROM `users` WHERE login=?', [$login]);
    }
    public static function getAllUsers() {
        return Database::executeFetchAll('SELECT id, name, login, status, role_id FROM `users`');
    }
    public static function createUser(string $name, string $login, string $phone, string $password, int $role_id) {
        Database::execute('INSERT INTO users(name, login, phone, password, role_id) VALUES(?,?,?,?,?)', [$name,$login,$phone,$password,$role_id]);

        return Database::$pdo->lastInsertId();
    }
    public static function makeWorkshiftInactive(int $id) {
        Database::execute('UPDATE `work_shifts` SET active=? WHERE id=?', [0, $id]);
    }
}