<?php
class Db
{
    private $conn;
    private $whereStr ="";
    function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "oop_database");
        if ($this->conn->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->conn->connect_error;
            exit();
        }
    }


    public function where($column,$value,$oper = '='){

        if(($this->whereStr)){
            $this->whereStr = $this->whereStr." AND $column $oper '$value' ";
        }else{
            $this->whereStr =" WHERE $column $oper '$value'";
        }

        return $this;
    }
    public function orWhere($column,$value,$oper = '='){

        if(($this->whereStr)){
            $this->whereStr = $this->whereStr." OR $column $oper '$value' ";
        }else{
            $this->whereStr =" WHERE $column $oper '$value'";
        }

        return $this;
    }


    public function select($sql)
    {
        $arrResult = [];
        $result = $this->conn->query($sql);
        if($result->num_rows == 1){
            return $result->fetch_assoc();
        }else {
            while($row = $result->fetch_assoc()) {
                $arrResult[] = $row;
            }
            return $arrResult;
        }
    }

    public function insert($tbl_name, $data)
    {
        $sql = "INSERT INTO $tbl_name (".implode(",",array_keys($data)) . ") VALUES ('".implode("','",array_values($data)) ."')"  ;
        return $this->conn->query($sql);
    }

    public function update($table_name, $data)
    {
        $where_condition =$this->whereStr;
        $this->whereStr = "";
        $updateData="";
        foreach ($data as $key => $value){
            $updateData .=$key. "= '".$value."' , ";

        }
        $updateData = substr($updateData,0,-2);
        $sql = "UPDATE  $table_name  SET   $updateData $where_condition";

        if ($this->conn->query($sql) === TRUE) {
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

    public function delete($table_name)
    {
        $where_condition = $this->whereStr;
        $this->whereStr = "";

        $sql = "DELETE FROM  $table_name   $where_condition";
        if ($this->conn->query($sql) === TRUE) {
            return "New record created successfully";
        } else {
            return "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

}


$db = new Db;
