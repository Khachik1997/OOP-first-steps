<?php


class  Db extends mysqli
{
    private $whereStr ;
    public function  __construct()
    {

        parent::__construct("localhost","root","","oop_database");
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
    }


    public function where($column,$value,$oper = '='){
        if($this->whereStr){
            $this->whereStr = $this->whereStr." AND $column $oper '$value' ";
        }else{
            $this->whereStr =" WHERE $column $oper '$value'";
        }
        return $this;
    }
    public function orWhere($column,$value,$oper = '='){
        if($this->whereStr){
            $this->whereStr = $this->whereStr." OR $column $oper '$value' ";
        }else{
            $this->whereStr =" WHERE $column $oper '$value'";
        }
        return $this;
    }


    public function select($sql)
    {
        $arrResult = [];
        $result = parent::query($sql);
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
            $dataVal.=  "'" .parent::real_escape_string($value) . "',";
        }
        $dataVal = substr($dataVal,0,-1);

        $sql = "INSERT INTO $tbl_name (".implode(",",array_keys($data)) . ") VALUES ($dataVal)";
        return parent::query($sql);
    }
    public function update($table_name, $data)
    {
        $where_condition = $this->whereStr;
        $this->whereStr = null;
        $updateData="";
        foreach ($data as $key => $value){
            $updateData .=$key. "= '".$this->conn->real_escape_string($value) ."',";
        }
        $updateData = substr($updateData,0,-1);
        $sql = "UPDATE  $table_name  SET   $updateData $where_condition";
        return parent::query($sql);
    }

    public function delete($table_name)
    {
        $where_condition = $this->whereStr;
        $this->whereStr = null;
        $sql = "DELETE FROM  $table_name   $where_condition";
        return parent::query($sql);
    }

}
$obj = new Db;
$result = $obj->select("SELECT * FROM user");
var_dump($result);