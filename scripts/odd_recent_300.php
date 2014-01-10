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

foreach ($data as $tmp){
    $code = $tmp['item_code'];
    $item_date = $tmp['item_date'];
    $tmp = $util->getRecent300V2List($item_date);
    $ret = '';
    for($start = 0;$start<3;$start++){
        $item_code = substr($code,$start,3);
        $recent_300 = array();
        foreach ($tmp as $v) {
            $recent_300[] = substr($v['item_code'],$start,3);
        }
        $recent_300 = array_unique($recent_300);
        $end = array_diff($odd_list,$recent_300);
        if(in_array($item_code,$end)){
            $ret .= '1';
        }else{
            $ret .= '0';
        }
    }
    $sql = "update shishicai set odd_recent_300_v2='{$ret}' where item_date='{$item_date}'";
    echo $sql . PHP_EOL;
    break;
}