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

/**
 * 从抓取到的html中读取中奖记录
 * @param string $html
 * @return obj
 */
function get_data($html){
    preg_match('/aryIssue=(.*?);/',$html,$matches);
    $str = $matches[1];
    $arr = json_decode($str,true);
    $new = array_pop($arr);
    return $new;
}

function update_data($url){
    $minute = date('i');
    while(date('i') === $minute){
        $html = get_html($url);
        $data = get_data($html);
        $item_date = $data['i'];
        $item_code = $data['b'];
        if(!empty($item_date) && !empty($item_code) && preg_match('/^\d{5}$/',$item_code) > 0){  
            $sql = 'insert into shishicai(item_date,item_code)values("%s","%s") on duplicate key update item_code=values(item_code)';
            $sql = sprintf($sql,$item_date,$item_code);
            $db = new LotteryDBHelper();
            $db->update($sql);
        }else{
            file_put_contents(ROOT_DIR . 'log/update_lottery.log',  date('[Y-m-d H:i:s]') . "\t" . "failed to update!");
        }
        sleep(1);
    }
}

update_data(Shishicai::URL);
