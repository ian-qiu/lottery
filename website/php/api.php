<?php

include dirname(__FILE__) . '/../../config/global.php';
include ROOT_DIR . 'db/lottery_db_helper.php';

include_once(ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php');
//@ini_set("display_errors","On");
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
        $json['codes'] = implode(' ', $ret);
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
            $item_date = date('Ymd',strtotime('tomorrow',strtotime(substr($date,0,8)))) . '-001';
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
        $json['item_date'] = $item_date . '-近三百天';
        $json['codes'] = implode(' ', $ret);
        echo json_encode($json);
        exit;
    }
    
    private function get_recent_items3(){
        $db = new LotteryDBHelper();
        $sql = "select * from shishicai order by item_date desc limit 1;";
        $data = $db->getOne($sql);
        $item_date = $data['item_date'];
        list($date,$issue) = explode('-',$item_date);
        if($issue == '120'){
            $item_date = date('Ymd',strtotime('tomorrow',strtotime(substr($date,0,8)))) . '-001';
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
        $total = array();
        for($i=0;$i<10;$i++){
            for($j=0;$j<10;$j++){
                for($k=0;$k<10;$k++){
                    $total[] = strval($i) . strval($j) . strval($k);
                }
            }
        }
        $total = array_diff($total,$ret);
        include ROOT_DIR . 'config/data.php';
        $ret = array_unique(array_intersect($odd_list,$total));
        //sort($ret,SORT_STRING);
        $json = array();
        $json['item_date'] = $item_date . '-奇数期&近三百天（共' . strval(count($ret)) . '注）';
        $json['codes'] = implode(' ', $ret);
        echo json_encode($json);
        exit;
    }
}

$c = new LotteryApi();
$c->dispatcher();