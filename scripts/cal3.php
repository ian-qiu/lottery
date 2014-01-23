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

set_time_limit(0);
@ini_set('memory_limit', '256M');

function calCodes($item_date, $last_code) {
    $util = new LotteryUtil();
    $total = LotteryUtil::getTotalCodes();
    $data = $util->getRecent300V2List($item_date);
    foreach ($data as $tmp) {
        $tmp_code = substr($tmp['item_code'], 2);
        $index = array_search($tmp_code, $total);
        if ($index !== false) {
            unset($total[$index]);
        }
    }
    global $odd_list;
    $codes = array_intersect($total, $odd_list);
    $ret = array();
    $last_code_sum = LotteryUtil::calSumValue(substr($last_code, 2));
    $last_code_trend = LotteryUtil::calTrendCode(substr($last_code, 2));
    foreach ($codes as $code) {
        if (LotteryUtil::isStraightCode($code)) {
            continue;
        }
        $sum = LotteryUtil::calSumValue($code);
        if ($sum == $last_code_sum) {
            continue;
        }
        $code_trend = LotteryUtil::calTrendCode($code);
        if ($last_code_trend == $code_trend) {
            continue;
        }
        $ret[] = $code;
    }
    return $ret;
}

$file = "/tmp/shishicai.txt";
$line_count = 0;
$cal_count = 150000;
$handle = fopen($file, 'r');
$line = trim(fgets($handle, 4096));
list($item_date, $item_code) = preg_split('/\s+/', $line);
while (true) {
    $line = trim(fgets($handle, 4096));
    list($last_item_date, $last_item_code) = preg_split('/\s+/', $line);
    $codes = calCodes($item_date, $last_item_code);
    $codes_count = count($codes);
    if(in_array(substr($item_code, 2),$codes)){
        echo "1\t" . $codes_count . PHP_EOL;
    }else{
        echo "0\t" . $codes_count . PHP_EOL;
    }
    $item_code = $last_item_code;
    $item_date = $last_item_date;
    if($line_count++ == 100000){
        break;
    }
    if (feof($handle)) {
        break;
    }
}

fclose($handle);