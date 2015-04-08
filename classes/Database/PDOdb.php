<?php


namespace classes\Database;

class PDOdb
{

//usage http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
//$database = new Database();
//$database->preQuery('INSERT INTO mytable (FName, LName, Age, Gender) VALUES (:fname, :lname, :age, :gender)');
//$database->bind(':fname', 'John');
//$database->bind(':lname', 'Smith');
//$database->bind(':age', '24');
//$database->bind(':gender', 'male');
//$database->execute();

    private $logger;
    private $username = 'root';
    private $password = '123';
    private $stmt;
    private $conn;

    public function __construct($logger =null)
    {
        $this->logger = $logger;

        try {
            $this->conn = new \PDO('mysql:host=localhost;dbname=ecommerce', $this->username, $this->password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            $this->logger->addInfo($e->getMessage());
            throw new \Exception( 'ERROR: ' . $e->getMessage());
        }
    }
//
//    public function preQuery($query)
//    {
//        $this->stmt = $this->conn->prepare($query);
//    }

    public function query($query)
    {
        $this->stmt = $this->conn->prepare($query);
    }

    public function fetchAll(){
        $this->execute();
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function single(){
        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchOneById($id,$table){
        $this->query("SELECT * FROM $table WHERE id=:id");
        $this->bind(":id", $id);
        $this->execute();
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function deleteById($id,$table){
        $count = $this->conn->exec("DELETE FROM $table WHERE id = $id");
        return "Deleted $count rows.\n";
    }

    //run the assigned statement
    public function execute(){
        return $this->stmt->execute();
    }


    public function rowCount(){
        return $this->stmt->rowCount();
    }

    public function lastInsertId(){
        return $this->conn->lastInsertId();
    }

    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function getDb()
    {
        return $this->conn;
    }

    public function beginTransaction(){
        return $this->conn->beginTransaction();
    }

    public function endTransaction(){
        return $this->conn->commit();
    }

    public function cancelTransaction(){
        return $this->conn->rollBack();
    }

}