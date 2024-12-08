<?php
namespace App\lib;

use App\Models\User;

use PDO;
use PDOException;
use SessionHandlerInterface;



class Session implements SessionHandlerInterface {
    private $user;
    private static $dbConnection;

    public function __construct(){
        try{
            $this->checkSessionExists();
        } catch (\Exception $e){
            Logger::getLogger()->critical("Session creation errors: ", ['exception' => $e]);
            die();
        }
        session_set_save_handler($this,true);
        session_start();
        if (isset($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
        }
    }

    public function __destruct(){
        session_write_close();
    }

    public function open(string $path, string $name): bool{
        try {
            // Create a new PDO connection
            self::$dbConnection = new PDO("mysql:host=". DB_HOST .";dbname=". DB_NAME, DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            Logger::getLogger()->critical("could not create DB connection: ", ['exception' => $e]);
            die();
            return true;
        }

        return isset(self::$dbConnection);
    }

    public function close(): bool {
        self::$dbConnection = null;
        return true;
    }

    public function read(string $id): string|false {
        try {
            $sql = "SELECT data FROM sessions WHERE id = :id";
            $stmt = self::$dbConnection->prepare($sql);
            $stmt->execute(compact('id'));
            if ($stmt->rowCount() === 1) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['data'];
            } else{
                return "";
            }
        } catch (PDOException $e) {
            Logger::getLogger()->critical("could not execute query: ", ['exception' => $e]);
            die();
        }
        return '';
    }


    public function write(string $id, string $data): bool {
        try {
            $sql = "REPLACE INTO sessions (id, data) 
                    VALUES (:id, :data)";
            $stmt = self::$dbConnection->prepare($sql);
            $result = $stmt->execute(compact('id', 'data'));
            return $result;
        } catch (PDOException $e) {
            Logger::getLogger()->critical("could not execute query: ", ['exception' => $e]);
            die();
        }
    }


    public function destroy(string $id): bool {
        try {
            $sql = "DELETE FROM sessions WHERE id = :id";
            $stmt = self::$dbConnection->prepare($sql);
            $return = $stmt->execute(compact('id'));
            return $return;
        } catch (PDOException $e) {
            Logger::getLogger()->critical("could not execute query: ", ['exception' => $e]);
            die();
        }
    }



    public function gc(int $max_lifetime): int|false {
        try {
            $sql = "DELETE FROM sessions WHERE DATE_ADD(last_accessed, INTERVAL $max_lifetime SECOND) < NOW()";
            $stmt = self::$dbConnection->prepare($sql);
            $result =  $stmt->execute();
            return $result ? $stmt->rowCount() : false;
        } catch (PDOException $e) {
            Logger::getLogger()->critical("could not execute query: ", ['exception' => $e]);
            die();
        }
    }


    public function checkSessionExists(){
        if (session_status() == PHP_SESSION_ACTIVE)
            throw new \Exception("Session already active");
    }

    public function isLoggedIn(){
        return $this->user;
    }

    public function getUser(){
        return $this->user;
    }

    public function login(User $userObj): bool{
        $this->user = $userObj;
        $_SESSION['user'] = $userObj;
        return true;
    }

    public function logout(){
        $this->user = false;
        $_SESSION = [];
        session_destroy();
        return true;
    }
}