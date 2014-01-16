<?php
include dirname(__FILE__) . '/../../config/global.php';
include_once ROOT_DIR . 'db/lottery_db_helper.php';
include_once ROOT_DIR . 'model/lottery_util.php';
include_once ROOT_DIR . 'model/Doraemon.php';
include_once ROOT_DIR . 'model/BaseController.php';
class PageController extends BaseController{
    
    public function index(){
        $sql = "select * from shishicai_codes";
        $db = new LotteryDBHelper();
        $data = $db->getAll($sql);
        $sql = "select item_date,item_code from shishicai order by item_date desc limit 100";
        $list = $db->getAll($sql);
        $code_names = array();
        $hits = array();
        $max_miss = array();
        foreach ($data as $id => $v){
            $code_names[] = $v['codes_desc'];
            $codes = explode(',', $v['codes']);
            $miss = 0;
            foreach($list as $tmp){
                if(in_array(substr($tmp['item_code'],2), $codes)){
                    break;
                }else{
                    $miss++;
                }
            }
            $hits[] = $miss;
            $max_miss[] = intval($v['max_miss']);
        }
        $high_charts_setting = array (
            'chart' =>array (
                'type' => 'column',
             ),
            'title' =>array (
               'text' => '最大连挂分布',
            ),
            'subtitle' =>array (
               'text' => 'www.shishicai.cn',
            ),
            'xAxis' =>array (
               'categories' => $code_names,
            ),
            'yAxis' =>array (
                'tickInterval' => 1,
                'max' => 20,
               'min' => 0,
               'title' => array (
                   'text' => '最大连挂次数 (次)',
               ),
            ),
            'plotOptions' =>array (
                'column' => array (
                    'pointPadding' => 0,
                     'borderWidth' => 2,
                 ),
             ),
            'series' =>array (
               array (
                   'name' => '当前最大连挂次数',
                   'data' => $hits,
                    'color' => 'green',
               ),
                array (
                   'name' => '历史最大连挂次数',
                   'data' => $max_miss,
                    'color' => 'red',
               )
            )
         );
        $this->output["high_charts_setting"] = json_encode($high_charts_setting);
        $this->display("tpl.maxmiss.html");
    }
    
    public function showCodes(){
        $this->display("tpl.addcodes.html");
    }
    
    public function addCodes(){
        $codes = $this->getParam('codes');
        $codes = trim($codes);
        $codes = preg_split('/\s+/', $codes);
        $codes = implode(',',$codes);
        $codes_desc = $this->getParam('codes_desc');
        $id = $this->getParam('id');
        $create_time = time();
        if(empty($codes) || empty($codes_desc)){
            header('Location:maxmiss.php?action=showCodes');
            exit;
        }
        if($id){
            $sql = "update shishicai_codes set codes_desc='$codes_desc',codes='$codes',max_miss=0";
        }else{
            $sql = "insert into shishicai_codes (codes_desc,codes,create_time)values('$codes_desc','$codes',$create_time)"; 
        }
        $db = new LotteryDBHelper();
        $db->update($sql);
        header('Location:maxmiss.php?action=showCodes');
    }
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "index";
$p = new PageController();
if(method_exists($p, $action)){
    $p->$action();
}else{
    die("Action not exists!");
}
