<?php 

namespace App\Database;
use PDO;
use PDOException;

abstract class Db {

    public $db;
    protected $user = 'root';
    protected $database = 'fscode_tgbot';
    protected $password = 'root';

    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=$this->database", $this->user, $this->password);
        } catch (PDOException $e) {
            error_log($e->getMessage() . PHP_EOL, 3, $_SERVER['DOCUMENT_ROOT'].'/../src/storage/error.log');
            die();
        }
    }


}