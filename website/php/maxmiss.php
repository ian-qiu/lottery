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
    
    public function addCodes(){
        $this->display("tpl.addcodes.html");
    }
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "index";
$p = new PageController();
if(method_exists($p, $action)){
    $p->$action();
}else{
    die("Action not exists!");
}