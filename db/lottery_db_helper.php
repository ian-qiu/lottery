<?php

class LotteryDBHelper{
    const DB = 'lottery';
    private $conn;
    private function connect(){
        if(!$this->conn){
            $this->conn = mysql_connect(DB::HOST, DB::USER, DB::PASSWD);
            mysql_select_db(self::DB,$this->conn);
        }
    }
    
    private function close(){
        if($this->conn){
            mysql_close($this->conn);
        }
    }
    
    public function getAll($sql,$assoc = ''){
        $this->connect();
        $result = mysql_query($sql, $this->conn);
        $ret = array();
        while($row = mysql_fetch_assoc($result)){
            if($assoc){
                $ret[$row[$assoc]] = $row;
            }else{
                $ret[] = $row;
            }
        }
        return $ret;
    }
    
    public function update($sql){
        $this->connect();
        $result = mysql_query($sql,$this->conn);
        return $result;
    }
    
    public function __destruct() {
        $this->close();
    }
}