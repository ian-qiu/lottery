<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include dirname(__FILE__) . '/../config/global.php';
include dirname(__FILE__) . '/../config/data.php';

include_once ROOT_DIR . 'db/lottery_db_helper.php';

include_once ROOT_DIR . 'model/lottery_util.php';

$util = new LotteryUtil();
$db = new LotteryDBHelper();
$sql = "select item_code from shishicai where item_date > '20130101-001' order by item_date desc;";
$data = $db->getAll($sql);

$last_hit = 0;
$hit_count = 0;
$ret = array();
foreach ($data as $tmp){
    $hit = substr($tmp['item_code'],2);
    if($last_hit == 0){
        $last_hit = $hit;
    }
    if($hit == $last_hit){
        $hit_count++;
    }else{
        if($hit == 2){
            $ret[$hit_count]++;
            $hit_count = 1;
        }
    }
}

ksort($ret);
print_r($ret);