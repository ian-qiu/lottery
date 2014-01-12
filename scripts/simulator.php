<?php
include dirname(__FILE__) . '/../config/global.php';
include dirname(__FILE__) . '/../config/data.php';

include_once ROOT_DIR . 'db/lottery_db_helper.php';

include_once ROOT_DIR . 'model/lottery_util.php';

$util = new LotteryUtil();
$db = new LotteryDBHelper();

Class Simulator{
    // 本金
    public $benjin = 3100;
    public $danzhu = 0.02;
    public $award = 19.4;
    public $liangua = 0;
    public $beitou = array(1,3,8,23,61,162);
    
    public $test_start = '20140111-024';
    public $test_end = '20140112-023';
    
    public function test(){
        $sql = "select * from shishicai where item_date>='%s' and item_date<='%s' order by item_date asc";
        global $db;
        $data = $db->getAll($sql);
        foreach($data as $v){
            $code = substr($v['item_code'],2,3);
            $codes = $this->getOddRecent300($item_date);
            $beishu = $this->getBeishu();
            $touru = $beishu * count($codes) * 0.02;
            $this->benjin -= $touru;
            $hit = in_array($code,$codes);
            if($hit){
                $this->liangua = 0;
                $award = $beishu * 19.4;
            }else{
                $this->liangua += 1;
                $award = 0;
            }
            $this->benjin += $award;
        }
        return $this->benjin;
    }
    
    private function getBeishu(){
        if($liangua >= 6){
            echo "连挂6次！Game Over!";
        }
        return $this->beitou[$liangua];
    }
    
    private function getOddRecent300($item_date){
        global $util;
        $list = $util->getRecent300V2List($item_date);
        $ret = array();
        foreach($list as $v){
            $ret[] = substr($v['item_code'],2);
        }
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
        return $ret;
    }
}

$sim = new Simulator();