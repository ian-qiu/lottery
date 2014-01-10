<?php
include dirname(__FILE__) . '/../config/global.php';

include_once ROOT_DIR . 'db/lottery_db_helper.php';

include_once ROOT_DIR . 'model/lottery_util.php';

$util = new LotteryUtil();
$db = new LotteryDBHelper();
$sql = "select * from shishicai order by item_date desc limit 1200;";
$data = $db->getAll($sql);
foreach ($data as $v){
    $item_date = $v['item_date'];
    $item_code = $v['item_code'];
    $recent_300_v1 = $util->calRecent300V1($item_date, $item_code);
    $recent_300_v2 = $util->calRecent300V2($item_date, $item_code);
    $sql = sprintf("update shishicai set recent_300_v1='%s',recent_300_v2='%s' where item_date='%s';",$recent_300_v1,$recent_300_v2,$item_date);
    $db->update($sql);
    //echo $sql . PHP_EOL;
}
