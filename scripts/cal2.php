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

for($i = 6;$i<19;$i++){
    $file = ROOT_DIR . '/config/500codes/' . strval($i) . '.txt';
    $contents = file_get_contents($file);
    $contents = trim($contents);
    $codes = preg_split('/\s+/', $contents);
    $codes = implode(',',$codes);
    $codes_desc = '号码-' . strval($i);
    $create_time = time();
    $sql = "insert into shishicai_codes (codes,codes_desc,create_time)values('$codes','$codes_desc',$create_time)";
    $db->update($sql);
}