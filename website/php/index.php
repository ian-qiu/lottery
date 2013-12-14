<?php

include dirname(__FILE__) . '/../../config/global.php';
include_once ROOT_DIR . 'db/lottery_db_helper.php';
include_once ROOT_DIR . 'model/lottery_util.php';

include_once(ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php');

$db = new LotteryDBHelper();
$sql = 'select * from shishicai order by item_date desc limit 800';
$codes = $db->getAll($sql,'item_date');
$codes = array_reverse($codes,true);

$util = new LotteryUtil();
foreach ($codes as $k => $v){
    $codes['last_300_codes'] = $util->getRecentIssues($v['item_date'], 300);
}
$json = json_encode(array('codes' => $codes));

$smarty_options = array(
    'left_delimiter' => '{{',
    'right_delimiter' => '}}',
    'template_dir' => ROOT_DIR . 'website/templates',
    'compile_dir' => ROOT_DIR . 'website/compile',
);
$s = new Smarty();
foreach ($smarty_options as $option => $v){
    $s->$option = $v;
}

$s->assign('JsonData',$json);
$s->display('tpl.index.html');

