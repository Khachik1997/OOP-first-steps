<?php
class Db
{
    private $conn;
    private $whereStr ;

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
        $dataVal = "";
        foreach ($data as $key => $value){
            $dataVal.=  "'" .$this->conn->real_escape_string($value) . "',";
        }
        $dataVal = substr($dataVal,0,-1);
        $sql = "INSERT INTO $tbl_name (".implode(",",array_keys($data)) . ") VALUES ($dataVal)"  ;
        return $this->conn->query($sql);
    }
    public function update($table_name, $data)
    {
        $where_condition = $this->whereStr;
        $this->whereStr = "";
        $updateData="";
        foreach ($data as $key => $value){
            $updateData .=$key. "= '".$this->conn->real_escape_string($value) ."',";
        }
        $updateData = substr($updateData,0,-2);
        $sql = "UPDATE  $table_name  SET   $updateData $where_condition";
        return $this->conn->query($sql);
    }

    public function delete($table_name)
    {
        $where_condition = $this->whereStr;
        $this->whereStr = "";
        $sql = "DELETE FROM  $table_name   $where_condition";
        return $this->conn->query($sql);
    }

}


$db = new Db;

