<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class LotteryUtil{
    
    public function getRecentIssues($item_date,$num){
        $dir = ROOT_DIR . 'log/recent/issues/';
        $file = $dir . $item_date . '.txt';
        if(is_file($file)){
            $codes = file_get_contents($file);
            return explode(',',$codes);
        }
        if(!is_dir($dir)){
            mkdir($dir,755);
        }
        $codes = $this->getCodesByDate($item_date, $num);
        file_put_contents($file, implode(',',$codes));
        return $codes;
    }
    
    private function getCodesByDate($item_date,$num){
        list(,$issue) = explode('-',$item_date);
        $sql = sprintf('select * from shishicai where item_date < "%s" and item_date like "%%%s" order by item_date desc limit %d',$item_date,$issue,$num);
        $db = new LotteryDBHelper();
        $data = $db->getAll($sql);
        $ret = array();
        foreach($data as $tmp){
            $ret[] = $tmp['item_code'];
        }
        return array_unique($ret);
    }
}
