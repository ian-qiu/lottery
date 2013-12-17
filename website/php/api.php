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
        $ret = array_unique($ret);
        //sort($ret,SORT_STRING);
        $json = array();
        $json['codes'] = implode(',', $ret);
        echo json_encode($json);
        exit;
    }
    
    private function get_recent_items2(){
        $db = new LotteryDBHelper();
        $sql = "select * from shishicai order by item_date desc limit 1;";
        $data = $db->getOne($sql);
        $item_date = $data['item_date'];
        list($date,$issue) = explode('-',$item_date);
        if($issue == '120'){
            $item_date = date('Ymd',strtotime('tomorrow',strttotime($date))) . '-001';
        }else{
            $item_date = $date . '-' . sprintf('%03d',intval($issue) + 1);
        }
        include_once ROOT_DIR . 'model/lottery_util.php';
        $util = new LotteryUtil();
        $list = $util->getRecent300V2List($item_date);
        $ret = array();
        foreach($list as $v){
            $ret[] = substr($v['item_code'],2);
        }
        $ret = array_unique($ret);
        //sort($ret,SORT_STRING);
        $json = array();
        $json['item_date'] = $item_date;
        $json['codes'] = implode(',', $ret);
        echo json_encode($json);
        exit;
    }
}

$c = new LotteryApi();
$c->dispatcher();