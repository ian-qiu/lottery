<?php
include dirname(__FILE__) . '/../../config/global.php';
include_once ROOT_DIR . 'db/lottery_db_helper.php';
include_once ROOT_DIR . 'model/lottery_util.php';
include_once ROOT_DIR . 'model/Doraemon.php';
include_once ROOT_DIR . 'model/BaseController.php';
include_once ROOT_DIR . 'config/data.php';
class PageController extends BaseController{
    
    public function index(){
        $start_date = $this->getParam("start_date",  date("Ymd"));
        $sql = "select item_date,item_code from shishicai where item_date < '$start_date-121' order by item_date desc limit 21";
        $db = new LotteryDBHelper();
        $data = $db->getAll($sql);
        $ret = array();
        foreach ($data as $k => $tmp) {
            if($k == 20){
                continue;
            }
            $codes = $this->calCodes($tmp['item_date'], $tmp['item_code'], $data[$k+1]['item_code']);
            $tmp['codes'] = $codes;
            $tmp['hit'] = in_array(substr($tmp['item_code'],2), $codes);
            $tmp['count'] = count($codes);
            $ret[] = $tmp;
        }
        $this->output['list'] = $ret;
        $this->display("tpl.hit.html");
    }
    
    private function calCodes($item_date,$item_code,$last_code){
        $util = new LotteryUtil();
        $total = LotteryUtil::getTotalCodes();
        $data = $util->getRecent300V2List($item_date);
        $code = substr($item_code, 2);
        foreach ($data as $tmp){
            $tmp_code = substr($tmp['item_code'],2);
            $index = array_search($tmp_code, $total);
            if($index !== false){
                unset($total[$index]);
            }
        }
        global $odd_list;
        $codes = array_intersect($total, $odd_list);
        $ret = array();
        $last_code_sum = LotteryUtil::calSumValue(substr($last_code,2));
        foreach ($codes as $code){
            if(LotteryUtil::isStraightCode($code)){
                continue;
            }
            $sum = array_sum(str_split($code));
            if($sum == $last_code_sum){
                continue;
            }
            $ret[] = $code;
        }
        return $ret;
    }
    
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "index";
$p = new PageController();
if(method_exists($p, $action)){
    $p->$action();
}else{
    die("Action not exists!");
}