<?php
include dirname(__FILE__) . '/../../config/global.php';
include_once ROOT_DIR . 'db/lottery_db_helper.php';
include_once ROOT_DIR . 'model/lottery_util.php';
include_once ROOT_DIR . 'model/Doraemon.php';
include_once ROOT_DIR . 'model/BaseController.php';
class PageController extends BaseController{
    
    public function index(){
        $this->display("tpl.maxmiss.html");
    }
    
    public function showCodes(){
        $this->display("tpl.addcodes.html");
    }
    
    public function addCodes(){
        $codes = $this->getParam('codes');
        $codes = preg_split('/\s+/', $codes);
        $codes = implode(',',$codes);
        $codes_desc = $this->getParam('codes_desc');
        $id = $this->getParam('id');
        $create_time = time();
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