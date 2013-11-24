<?php

include '../config/global.php';
include ROOT_DIR . 'db/lottery_db_helper.php';

inlcude_once(ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php');

$db = new LotteryDBHelper();
$sql = 'select * from lottery order by item_date desc limit 200';
$data = $db->getAll($sql);
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
foreach ($smarty_options as $k => $v){
    $s->$k = $v;
}

$s->assign('JsonData',$json);
$s->display('tpl.index.html');

