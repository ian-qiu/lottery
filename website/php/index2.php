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
        $this->output['start_date'] = $start_date;
        $sql = "select item_date,item_code from shishicai where item_date < '$start_date-121' order by item_date desc limit 49";
        $db = new LotteryDBHelper();
        $data = $db->getAll($sql);
        $next_issue = LotteryUtil::getNextIssue($data[0]['item_date']);
        $data = array_reverse($data);
        $data[] = array('item_date' => $next_issue);
        $ret = array();
        foreach ($data as $k => $tmp) {
            if($k == 0){
                continue;
            }
            $codes = $this->calCodes($tmp['item_date'], $data[$k-1]['item_code']);
            //$tmp['codes'] = $codes;
            if(isset($tmp['item_code'])){
                $tmp['hit'] = in_array(substr($tmp['item_code'],2), $codes);
            }
            $tmp['count'] = count($codes);
            $ret[] = $tmp;
        }
        $this->output['next_issue'] = $next_issue;
        $this->output['next_codes_count'] = count($codes);
        $this->output['next_codes'] = implode(" ", $codes);
        $this->output['list'] = $ret;
        $this->display("tpl.hit.html");
    }
    
    private function calCodes($item_date,$last_code){
        $util = new LotteryUtil();
        $total = LotteryUtil::getTotalCodes();
        $data = $util->getRecent300V2List($item_date);
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
        $last_code_trend = LotteryUtil::calTrendCode(substr($last_code,2));
        foreach ($codes as $code){
            if(LotteryUtil::isStraightCode($code)){
                continue;
            }
            $sum = LotteryUtil::calSumValue($code);
            if($sum == $last_code_sum){
                continue;
            }
            $code_trend = LotteryUtil::calTrendCode($code);
            if($last_code_trend == $code_trend){
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
