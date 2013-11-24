#!/bin/env php
<?php
include dirname(__FILE__) . '/../config/global.php';
set_time_limit(0);
@ini_set('memory_limit', '128M');
@ini_set('display_erros','On');
include ROOT_DIR . 'db/lottery_db_helper.php';
function get_html($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret;
}

function get_data($html){
    preg_match('/aryIssue=(.*?);/',$html,$matches);
    $str = $matches[1];
    $arr = json_decode($str,true);
    return $arr;
}

function update_data($url){
    $html = get_html($url);
    $data = get_data($html);
    $sql = 'insert into shishicai(item_date,item_code)values';
    foreach ($data as $tmp){
        $item_date = $tmp['i'];
        $item_code = $tmp['b'];
        if(!empty($item_date) && !empty($item_code) && preg_match('/^\d{5}$/',$item_code) > 0){  
            $sql .= sprintf('("%s","%s"),',$item_date,$item_code);
        }
    }
    $sql = trim($sql,',');
    $sql .= 'on duplicate key update item_code=values(item_code)';
    $db = new LotteryDBHelper();
    $db->update($sql);
}

update_data(Shishicai::URL);
