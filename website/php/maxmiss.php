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
        $sql = "select item_date,item_code from shishicai order by item_date desc limit 120";
        $list = $db->getAll($sql);
        $code_names = array();
        $hits = array();
        $max_miss = array();
        $drilldown = array();
        foreach ($data as $id => $v){
            $code_names[] = $v['codes_desc'];
            $codes = explode(',', $v['codes']);
            $miss = 0;
            $drilldown_tmp = array();
            foreach($list as $tmp){
                $tmp_code = substr($tmp['item_code'],2);
                if(in_array($tmp_code, $codes)){
                    $drilldown_tmp[] = 1;
                    break;
                }else{
                    $drilldown_tmp[] = 0;
                    $miss++;
                }
            }
            $drilldown[] = array(
                'name' => '号码-' . $id,
                'id' => $id,
                'data' => $this->processCodes($tmp),
            );
            $hits[] = array(
                'name' => '号码-' . $id,
                'drilldown' => '号码-' . $id,
                'y' => $miss
             );
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
                'type' => 'category',
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
                'series' => array(
                     'borderWidth'=> 0,
                    'dataLabels'=> array(
                        'enabled' => true,
                        'format' => '{point.y:.1f}%'
                    )
                ),
             ),
            'series' =>array (
               array (
                   'name' => '当前最大连挂次数',
                   'data' => $hits,
                   //'color' => 'green',
                   'colorByPoint'=>true,
               ),
                /*array (
                   'name' => '历史最大连挂次数',
                   'data' => $max_miss,
                    'color' => 'red',
               )*/
            ),
            'drilldown' => array(
                'series'=> $drilldown,
            ),
         );
        $this->output["high_charts_setting"] = json_encode($high_charts_setting);
        $this->display("tpl.maxmiss.html");
    }
    
    public function index2(){
        $sql = "select * from shishicai_codes";
        $db = new LotteryDBHelper();
        $data = $db->getAll($sql);
        $sql = "select item_date,item_code from shishicai order by item_date desc limit 120";
        $list = $db->getAll($sql);
        $code_names = array();
        $hits = array();
        $max_miss = array();
        $drilldown = array();
        foreach ($data as $id => $v){
            $code_names[] = $v['codes_desc'];
            $codes = explode(',', $v['codes']);
            $drilldown_tmp = array();
            foreach($list as $tmp){
                $tmp_code = substr($tmp['item_code'],2);
                if(in_array($tmp_code, $codes)){
                    $drilldown_tmp[] = 1;
                }else{
                    $drilldown_tmp[] = 0;
                }
            }
            $drilldown_data = $this->processCodes($drilldown_tmp);
            $drilldown[] = array(
                'name' => 'code-' . $id,
                'id' => $id,
                'data' => $drilldown_data,
            );
            $hits[] = array(
                'name' => 'code-' . $id,
                'drilldown' => 'code-' . $id,
                'y' => $drilldown_data[0][1],
             );
            //$max_miss[] = intval($v['max_miss']);
        }
        $this->output["brandsData"] = json_encode($hits);
        $this->output["drilldownSeries"] = json_encode($drilldown);
        $this->display("tpl.drilldown.html");
    }
    
    private function processCodes($data){
        $last = false;
        $count = 0;
        $ret = array();
        foreach ($data as $v) {
            if($last === false){
                $last = $v;
            }
            if($last === $v){
                $count++;
            }else{
                $ret[] = array(
                    $last === 0 ? 'miss' : 'hit',$count
                );
                $count = 1;
            }
            $last = $v;
        }
        $ret[] = array(
            $last === 0 ? 'miss' : 'hit',$count
        );
        return $ret;
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
