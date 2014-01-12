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
$sql = "select * from shishicai where item_date > '20140101-023'order by item_date desc;";
$data = $db->getAll($sql);

foreach ($data as $tmp){
    $code = $tmp['item_code'];
    $item_date = $tmp['item_date'];
    $tmp = $util->getRecent300V2List($item_date);
    $item_code = substr($code,2,3);
    $recent_300 = array();
    foreach ($tmp as $v) {
        $recent_300[] = substr($v['item_code'],$start,3);
    }
    $recent_300 = array_unique($recent_300);
    $end = array_diff($odd_list,$recent_300);
    if(in_array($item_code,$end)){
        $ret = 1;
    }else{
        $ret = 0;
    }
    $sql = "update shishicai set hit_v1={$ret} where item_date='{$item_date}';";
    echo $sql . PHP_EOL;
    //break;
}