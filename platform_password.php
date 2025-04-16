<?php
class PasswordPlatforms
{
    private $conn;
    private $table_name = 'platform';

    public function __construct($db)
    {
        $this->conn = $db;
    }
   

    public function savePasswordRecord($username, $platform_name, $password, $register_date)
    {
        // Insert password record into database
        $query = "INSERT INTO " . $this->table_name . " (username, platform_name, password, register_date) VALUES (:username, :platform_name, :password, :register_date)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':platform_name', $platform_name);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':register_date', $register_date);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public function updatePasswordRecord($id,  $platform_name, $password)
    {

        $query = "UPDATE " . $this->table_name . " SET password = :password, platform_name= :platform_name WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':platform_name', $platform_name);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    public function deletePasswordRecord($id)
    {

        $query = "Delete from  " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function userPasswords($username)
    {

        $query = "Select * from  " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return false;
    }

    public function verifyPasswordwithUsername($id,$username)
    {

        $query = "Select * from  " . $this->table_name . " WHERE username = :username and id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return $stmt->fetchObject();
        }
        return false;
    }
}