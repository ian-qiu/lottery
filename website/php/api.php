<?php

include dirname(__FILE__) . '/../../config/global.php';
include ROOT_DIR . 'db/lottery_db_helper.php';

include_once(ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php');

class LotteryApi{
    
    public function dispatcher(){
        $action = $_REQUEST['action'] ? $_REQUEST['action'] : 'index';
        if(is_string($action) && !empty($action) && method_exists($this, $action)){
            $this->{$action}();
        }
    }
    
    private function index(){
        die;
    }
    
    private function get_recent_items(){
        $limit = isset($_REQUEST['limit']) && $_REQUEST['limit'] > 0 ? $_REQUEST['limit'] : '300';
        $db = new LotteryDBHelper();
        $sql = 'select substr(item_code,3) as item_code_tmp from shishicai order by item_date desc limit ' . $limit;
        $data = $db->getAll($sql);
        $ret = array();
        foreach($data as $tmp){
            $ret[] = $tmp['item_code_tmp'];
        }
        $ret = implode(' ', array_unique($ret));
        echo $ret;
        exit;
    }
    
    private function get_recent_items2(){
        $db = new LotteryDBHelper();
        $sql = "select * from shishicai order by item_date desc limit 1;";
        $data = $db->getOne($sql);
        $item_date = $data['item_date'];
        $item_code = $data['item_code'];
        include_once ROOT_DIR . 'model/lottery_util.php';
        $util = new LotteryUtil();
        $list = $util->getRecent300V2List($item_date);
        $ret = array();
        foreach($list as $v){
            $ret[] = substr($v,2);
        }
        $ret = implode(' ', array_unique($ret));
        echo $ret;
        exit;
    }
}

$c = new LotteryApi();
$c->dispatcher();