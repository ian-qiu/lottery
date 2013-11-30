<?php

include dirname(__FILE__) . '/../../config/global.php';
include ROOT_DIR . 'db/lottery_db_helper.php';

include_once(ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php');

$db = new LotteryDBHelper();
$sql = 'select * from shishicai order by item_date desc limit 801';
$data = $db->getAll($sql);
$data = array_reverse($data);
foreach ($data as $v){
    $json[$v['item_date']] = $v['item_code'];
}
$json = json_encode($json);

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

