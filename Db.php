<?php

class Db
{
    private $mysqli;

    function __construct()
    {

        $this->mysqli = new mysqli("localhost", "root", "", "oop_database");

        if ($this->mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
            exit();
        }

    }

    public function select($sql)
    {
        $result = $this->mysqli->query($sql);
        var_dump($result->fetch_all());

    }

    public function insert($tbl_name, $data)
    {
        $name = $data['name'];
        $surname = $data['surname'];
        $email = $data['email'];
        $sql = "SELECT email FROM user WHERE email ='$email' ";
        $duplicate = $this->mysqli->query($sql);
        if($duplicate->num_rows > 0){
            return "That email is already in use";
        }else{
            $sql = "INSERT INTO $tbl_name (name,surname,email) VALUES ('$name','$surname','$email') ";
            if ($this->mysqli->query($sql) === TRUE) {
                return "New record created successfully";
            } else {
                return "Error: " . $sql . "<br>" . $this->mysqli->error;
            }
        }

    }

    public function update($table_name, $data, $id)
    {
        $name = $data['name'];
        $surname = $data['surname'];
        $email = $data['email'];
        $sql = "UPDATE $table_name Set  name = '$name',surname = '$surname',email = '$email' WHERE id = '$id'";
        if ($this->mysqli->query($sql) === TRUE) {
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . $this->mysqli->error;
        }
    }

    public function delete($table_name, $id)
    {
        $sql = "DELETE FROM $table_name WHERE  id ='$id'";
        if ($this->mysqli->query($sql) === TRUE) {
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . $this->mysqli->error;
        }
    }

}


$db = new Db;
//$arrResultData = $db->select("SELECT * FROM user");
$data = ["name" => "GAG", "surname" => "Smite", "email" => "John@.com"];
$boolResultSuccess = $db->insert("user", $data);


//$boolResultUpdate = $db->update('user', $data, 18);
//$boolResultDelete = $db->delete('user', "18");
echo $boolResultSuccess;