<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class LotteryUtil{
    
    public function calRecent300V1($item_date,$item_code,$num = 300){
        $db = new LotteryDBHelper();
        $sql = "select item_code from shishicai where item_date <'$item_date' order by item_date desc limit 300";
        $data = $db->getAll($sql);
        return $this->getRecent300($data, $item_code);
    }
    
    public function calRecent300V2($item_date,$item_code,$num = 300){
        $db = new LotteryDBHelper();
        list(,$issue) = explode('-',$item_date);
        $sql = sprintf('select * from shishicai where item_date < "%s" and item_date like "%%-%s" order by item_date desc limit %d',$item_date,$issue,$num);
        $data = $db->getAll($sql);
        return $this->getRecent300($data, $item_code);
    }
    
    private function getRecent300($data,$item_code,$num = 300){
        $t1 = '1';
        $t2 = '1';
        $t3 = '1';
        $v1 = substr($item_code,0,3);
        $v2 = substr($item_code,1,3);
        $v3 = substr($item_code,2,3);
        foreach($data as $tmp){
            $tmp_v1 = substr($tmp['item_code'],0,3);
            if($v1 === $tmp_v1){
                $t1 = '2';
            }
            $tmp_v2 = substr($tmp['item_code'],1,3);
            if($v2 === $tmp_v2){
                $t2 = '2';
            }
            $tmp_v3 = substr($tmp['item_code'],2,3);
            if($v3 === $tmp_v3){
                $t3 = '2';
            }
        }
        return $t1.$t2.$t3;
    }
}
