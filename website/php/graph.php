<?php

include dirname(__FILE__) . '/../../config/global.php';

include_once(ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php');

function calMaxMiss($hits) {
    $max = 0;
    $total = 0;
    foreach ($hits as $v) {
        if ($v == 0) {
            $total += 1;
        } else {
            if ($total > $max) {
                $max = $total;
            }
            $total = 0;
        }
    }
    if($max < 1){
        return $total;
    }
    return $max;
}

$smarty_options = array(
    'left_delimiter' => '{{',
    'right_delimiter' => '}}',
    'template_dir' => ROOT_DIR . 'website/templates',
    'compile_dir' => ROOT_DIR . 'website/compile',
);
$s = new Smarty();
foreach ($smarty_options as $option => $v) {
    $s->$option = $v;
}

$db = new LotteryDBHelper();
$start_date = '20140101-023';
$end_date = '20140110-024';
$sql = "select item_date,hit_v1 from shishicai where item_date>'$start_date' and item_date<'$end_date'";
$data = $db->getAll($sql);
$tmp = array();
foreach ($data as $v) {
    $tmp[$v['item_date']][] = $v['hit_v1'];
}
$ret = array();
$dates = array();
$hits = array();
foreach ($tmp as $item_date => $arr) {
    $dates[] = $item_date;
    $hits[] = calMaxMiss($hits);
}
/**
 * 
 */
$high_charts_setting = array (
'chart' =>array (
    'type' => 'column',
 ),
 'title' =>array (
    'text' => 'Monthly Average Rainfall',
 ),
 'subtitle' =>array (
    'text' => 'Source: WorldClimate.com',
 ),
 'xAxis' =>array (
    'categories' => $dates,
    ),
 'yAxis' =>
 array (
'min' => 0,
 'title' =>
 array (
'text' => 'Rainfall (mm)',
 ),
 ),
 'plotOptions' =>
 array (
'column' =>
 array (
'pointPadding' => 0.2,
 'borderWidth' => 0,
 ),
 ),
 'series' =>
    array (
        array (
            'name' => 'Tokyo',
            'data' => $hits
        )
    )
 );

$s->assign("high_charts_setting", json_encode($high_charts_setting));

$s->display('tpl.graph.html');
