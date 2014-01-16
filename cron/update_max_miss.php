#!/bin/env php
<?php
include dirname(__FILE__) . '/../config/global.php';
set_time_limit(-1);
@ini_set('memory_limit', '256M');
@ini_set('display_erros','On');
include ROOT_DIR . 'db/lottery_db_helper.php';

$db = new LotteryDBHelper();

$sql = "select * from shishicai_codes where max_miss < 1";

$data = $db->getAll($sql,'id');

foreach ($data as $key => $value) {
    $value['last_miss'] = false;
    $value['miss_count'] = 0;
    $value['max_miss_count'] = 0;
    $value['codes'] = explode(',', $value['codes']);
    $data[$key] = $value;
}

$sql = "select item_date,item_code from shishicai order by item_date desc";
$codes = $db->getAll($sql);

foreach($codes as $tmp){
    $code = substr($tmp['item_code'],2);
    foreach($data as $id => $v){
        if(!in_array($code,$v['codes'])){
            $data[$id]['miss_count']++;
			$data[$id]['last_miss'] = true;
        }else{
            if($data[$id]['last_miss']){
                if($data[$id]['max_miss_count'] < $data[$id]['miss_count']){
                    $data[$id]['max_miss_count'] = $data[$id]['miss_count'];
                }
                $data[$id]['miss_count']++;
            }
            $data[$id]['last_hit'] = false;
            $data[$id]['miss_count'] = 0;
        }
    }
}

foreach ($data as $id => $v){
    $max_miss = intval($v['max_miss_count']);
    $sql = "update shishicai_codes set max_miss=$max_miss where id=$id";
    echo $sql . PHP_EOL;
}
