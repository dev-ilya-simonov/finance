<?
namespace Finance;

class Model {

    const DB_NAME = 'gimasp_guestbook';
    const DB_USER = 'gimasp_guestbook';
    const DB_HOST = 'localhost';
    const DB_PASS = '1j6E*Gpg';
    const CHARSET = 'utf8';
    
    private $db;

    function __construct() {
        $this->db_connect();
    }

    private function db_connect() {
        try {
            //$this->$db = new \PDO("mysql:host=localhost;dbname=gimasp_guestbook", 'gimasp_guestbook', '1j6E*Gpg');
            $this->db = new \PDO(
                "mysql:host=".self::DB_HOST.";dbname=".self::DB_NAME,
                self::DB_USER,
                self::DB_PASS,
                $options = [
                    \PDO::ATTR_ERRMODE =>\PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES ".self::CHARSET
                ]
            );
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    public static function HelloWorld(){
        echo 'Hello World from Model!';
    }

    public function createDefaultTables() {        
        $table1 = "users";
        $table2 = "account";
        $table3 = "journal";
        try {
            $sql ="CREATE TABLE IF NOT EXISTS $table1(
                ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR( 30 ) NULL,
                second_name VARCHAR( 50 ) NULL, 
                login VARCHAR( 50 ) NOT NULL, 
                pass VARCHAR( 50 ) NOT NULL, 
                account_id INT( 11 ) NULL);";
            $this->db->exec($sql);   

            $sql ="CREATE TABLE IF NOT EXISTS $table2(
                ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                user_id INT( 11 ) NOT NULL,
                funds DOUBLE NULL);" ;
            $this->db->exec($sql);            

            $sql ="CREATE TABLE IF NOT EXISTS $table3(
                ID INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                user_id INT( 12 ) NOT NULL,
                action VARCHAR( 50 ) NOT NULL,
                sum FLOAT NOT NULL,
                info TEXT NOT NULL,
                date DATETIME DEFAULT CURRENT_TIMESTAMP);" ;
            $this->db->exec($sql);            

        } catch(\PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    public function add($table,$arValues) {
        $columns = [];
        $values = [];

        foreach($arValues as $k => $v):
            $columns[] = strtolower($k);
            $values[] = $v;
        endforeach;

        $columns = implode(',',$columns);
        $values = '"'.implode('","', $values).'"';

        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $this->db->lastInsertId();
        } catch(\PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
        
    }

    public function get($table,$arSelect,$arFilter,$arParams = []) {
        $select = [];
        $filter = [];
        $joinArr = [];
        $joinStr = '';
        
        $select = implode(',',$arSelect);
        foreach($arFilter as $k => $v):
            $filter[] = strtolower($k).' = "'.$v.'"';
        endforeach;
        $filter = implode(' AND ',$filter);

        if(!empty($arParams)) {
            if($arParams['JOIN']) {
                $joinStr .= 'JOIN ' . $arParams['JOIN']['TABLE'] . ' ON ';
                foreach($arParams['JOIN']['ON'] as $k => $v):
                    $joinArr[] = $k . ' = ' . $v;
                endforeach;
            }
        }
        $joinStr .= implode(',',$joinArr);
        $query = "SELECT $select FROM $table $joinStr WHERE $filter";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch(\PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
        
    }

    public function update($table,$arValues,$arFilter) {
        $set = [];

        foreach($arValues as $k => $v):
            $set[] = strtolower($k) . '=' . $v;
        endforeach;

        $set = implode(',',$set);

        foreach($arFilter as $k => $v):
            $filter[] = strtolower($k).' = "'.$v.'"';
        endforeach;
        $filter = implode(' AND ',$filter);

        $query = "UPDATE $table SET $set WHERE $filter";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $this->db->lastInsertId();
        } catch(\PDOException $e) {
            echo $e->getMessage();//Remove or change message in production code
        }
        
    }

}