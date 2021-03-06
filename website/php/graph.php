<?php

include dirname(__FILE__) . '/../../config/global.php';
include_once ROOT_DIR . 'db/lottery_db_helper.php';
include_once ROOT_DIR . 'model/lottery_util.php';

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

$fenge = isset($_REQUEST['fenge']) ? intval($_REQUEST['fenge']) : 6;

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
if(isset($_REQUEST['end_date'])){
    $end_date = $_REQUEST['end_date'];
}else{
    $end_date = date('Y-m-d',strtotime('tomorrow'));
}
$query_end_date = date('Ymd',strtotime($end_date)) . '-024';
if(isset($_REQUEST['start_date'])){
    $start_date = $_REQUEST['start_date'];
}else{
    $start_date = date('Y-m-d',strtotime('60 days ago'));
}
if(isset($_REQUEST['type'])){
	$type = $_REQUEST['type'];
}else{
	$type = 'odd_recent_300';
}

function get_codes($type){
	$file = ROOT_DIR . "/config/500codes/" . $type . '.txt';
	$contents = file_get_contents($file);
	return preg_split('/\s+/',$contents);
}

if($type != "odd_recent_300"){
	$codes = get_codes($type);
}

$types = array('odd_recent_300' => '奇数期&最近三百天','1' => '1','2' => '2');
$query_start_date .= date('Ymd',strtotime($start_date)) . '-023';
$sql = "select item_date,hit_v1 from shishicai where item_date>'$query_start_date' and item_date<'$query_end_date'";
$data = $db->getAll($sql);
$tmp = array();
foreach ($data as $v) {
    list($date,$issue) = explode('-',$v['item_date']);
    if($issue < '024'){
        $date = date('Ymd',strtotime($date) - 3600);
    }
	if($type == "odd_recent_300"){
		$tmp[$date][] = $v['hit_v1'];
	}else{
		$tmp[$date][] = in_array(substr($v['item_code'],2),$codes) ? 1 : 0;
	}
}
$ret = array();
$dates = array();
$hits = array();
$colors = array();
foreach ($tmp as $item_date => $arr) {
    $dates[] = $item_date;
    $hit_count = calMaxMiss($arr);
    if($hit_count > $fenge){
        $color = "red";
    }else{
        $color = "green";
    }
    $hits[] = array(
        'y' => $hit_count,
        'color' => $color,
    );
}

$high_charts_setting = array (
    'chart' =>array (
        'type' => 'column',
     ),
    'title' =>array (
       'text' => '奇数期&近300天最大连挂',
    ),
    'subtitle' =>array (
       'text' => 'www.shishicai.cn',
    ),
    'xAxis' =>array (
       'categories' => $dates,
    ),
    'yAxis' =>array (
        'tickInterval' => 1,
        'max' => 15,
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
           'name' => '024至次日023最大连挂次数',
           'data' => $hits
       )
    )
 );

$s->assign("start_date", $start_date);
$s->assign("end_date", $end_date);
$s->assign("types", $types);
$s->assign("high_charts_setting", json_encode($high_charts_setting));
$s->display('tpl.graph.html');
