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
        $data = $this->getRecent300V2List($item_date, $num);
        return $this->getRecent300($data, $item_code);
    }
    
    public function calRecent300V3($item_date,$item_code,$num = 300){
        include ROOT_DIR . 'config/data.php';
        $data = $this->getRecent300V2List($item_date, $num);
        $ret = '';
        for($start = 0;$start < 3;$start++){
            $codes = array();
            foreach ($data as $tmp){
                $codes[] = substr($tmp['item_code'],$start,3);
            }
            $codes = array_diff($odd_list, $codes);
            if(in_array(substr($item_code,$start,3),$codes)){
                $ret .= '1';
            }else{
                $ret .= '2';
            }
        }
        return $ret;
    }
    
    public function calHitV1($item_date,$item_code){
        $total = array();
        for($i=0;$i<10;$i++){
            for($j=0;$j<10;$j++){
                for($k=0;$k<10;$k++){
                    $total[] = strval($i) . strval($j) . strval($k);
                }
            }
        }
        $list = $this->getRecent300V2List($item_date);
        $ret = array();
        foreach($list as $tmp){
            $ret[] = substr($tmp['item_code'],2,3);
        }
        $list1 = array_diff($total,$ret);
        include ROOT_DIR . 'config/data.php';
        $end = array_intersect($odd_list, $list1);
        $code = substr($item_code,2,3);
        if(in_array($code,$end)){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function getRecent300V2List($item_date,$num = 300){
        $db = new LotteryDBHelper();
        list(,$issue) = explode('-',$item_date);
        $sql = sprintf('select * from shishicai where item_date < "%s" and item_date like "%%-%s" order by item_date desc limit %d',$item_date,$issue,$num);
        $data = $db->getAll($sql);
        return $data;
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
