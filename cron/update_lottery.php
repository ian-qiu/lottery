#!/bin/env php
<?php
include dirname(__FILE__) . '/../config/global.php';
set_time_limit(60);
@ini_set('memory_limit', '128M');
@ini_set('display_erros','On');
include ROOT_DIR . 'db/lottery_db_helper.php';
function get_html($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
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
function get_data2($html){
    preg_match('/aryIssue=(.*?);/',$html,$matches);
    $str = $matches[1];
    $arr = json_decode($str,true);
    if(!is_array($arr)){
        return false;
    }
    $sort = array();
    foreach($arr as $tmp){
        $sort[] = $tmp['i'];
    }   
    array_multisort($sort,SORT_ASC,SORT_STRING,$arr);
    $new = array_pop($arr);
    return $new;
}

function get_data($html){
    $arr = json_decode($html,true);
    if(is_array($arr) && isset($arr['successful']) && $arr['successful']){
        $items = $arr['latestPeriods'];
        $ret = array();
        foreach ($items as $tmp){
            $issue = $tmp['period'];
            $item_date = '20' . substr($issue,0,6) . '-' .substr($issue,6);
            $item_code = preg_replace('/\s+/', '', $tmp['number']);
            $ret[$item_date] = array(
                'i' => $item_date,
                'b' => $item_code
            );
        }
        ksort($ret);
        return array_pop($ret);
    }else{
        return false;
    }
}

function update_data($url){
    $minute = date('i');
    include_once ROOT_DIR . 'model/lottery_util.php';
    $util = new LotteryUtil();
    while(date('i') === $minute){
        $html = get_html($url);
        $data = get_data($html);
        if(!$data){
            continue;
        }
        $item_date = $data['i'];
        $item_code = $data['b'];
        if(!empty($item_date) && !empty($item_code) && preg_match('/^\d{5}$/',$item_code) > 0){
            $recent_300_v1 = $util->calRecent300V1($item_date, $item_code);
            $recent_300_v2 = $util->calRecent300V2($item_date, $item_code);
            $sql = 'insert into shishicai(item_date,item_code,recent_300_v1,recent_300_v2,create_time)values("%s","%s","%s","%s",%d) on duplicate key update item_code=values(item_code),recent_300_v1=values(recent_300_v1),recent_300_v2=values(recent_300_v2)';
            $sql = sprintf($sql,$item_date,$item_code,$recent_300_v1,$recent_300_v2,time());
            $db = new LotteryDBHelper();
            $db->update($sql);
        }else{
            file_put_contents(ROOT_DIR . 'log/update_lottery.log',  date('[Y-m-d H:i:s]') . "\t" . "failed to update!");
        }
        sleep(1);
    }
}

//update_data(Shishicai::URL);

update_data('http://caipiao.163.com/order/preBet_resentAwardNum.html?gameEn=ssc');