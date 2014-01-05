#!/bin/env php
<?php
include dirname(__FILE__) . '/../config/global.php';
set_time_limit(0);
@ini_set('memory_limit', '128M');
@ini_set('display_erros', 'On');
include ROOT_DIR . 'db/lottery_db_helper.php';

function update_data() {
    global $argc, $argv;
    if($argc != 3){
        die("params != 3");
    }
    $item_date = $argv[1];
    $item_code = $argv[2];
    if (!empty($item_date) && !empty($item_code) && preg_match('/^\d{5}$/', $item_code) > 0) {
        $recent_300_v1 = $util->calRecent300V1($item_date, $item_code);
        $recent_300_v2 = $util->calRecent300V2($item_date, $item_code);
        $sql = 'insert into shishicai(item_date,item_code,recent_300_v1,recent_300_v2,create_time)values("%s","%s","%s","%s",%d) on duplicate key update item_code=values(item_code),recent_300_v1=values(recent_300_v1),recent_300_v2=values(recent_300_v2)';
        $sql = sprintf($sql, $item_date, $item_code, $recent_300_v1, $recent_300_v2, time());
        $db = new LotteryDBHelper();
        $db->update($sql);
    } else {
        file_put_contents(ROOT_DIR . 'log/update_lottery.log', date('[Y-m-d H:i:s]') . "\t" . "failed to update!");
    }
}

update_data();
