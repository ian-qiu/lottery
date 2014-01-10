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
$sql = "select * from shishicai order by item_date desc limit 1200;";
$data = $db->getAll($sql);

$last_hit = 0;
$hit_count = 0;
$ret = array();
foreach ($data as $tmp){
    $hit = substr($tmp['odd_recent_300_v2'],-1);
    if($last_hit == 0){
        $last_hit = $hit;
    }
    if($hit == $last_hit){
        $hit_count++;
    }else{
        $ret[$hit_count]++;
        $hit_count = 0;
    }
}

ksort($ret);
print_r($ret);