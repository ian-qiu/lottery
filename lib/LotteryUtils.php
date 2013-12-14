<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LotteryUtils{
    public static function getCodeByDate($num,$item_date){
        list(,$issue) = explode('-',$item_date);
        $sql = sprintf('select * from shishicai where item_date < "%s" and item_date like "%s" limit %d',$item_date,$issue,$num);
        $db = new LotteryDBHelper();
        $data = $db->getAll($sql);
        $ret = array();
        foreach($data as $tmp){
            $ret[] = $tmp['item_code'];
        }
        return array_unique($ret);
    }
}