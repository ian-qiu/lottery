<?php
@ini_set('display_errors', 'On');
error_reporting(E_ALL);
$dir = dirname(__FILE__) . '/';
$file = $dir . '3000.txt';

if(!is_file($file)){
    die($file . ' not exists!');
}

$odd = array();
$even = array();
$total = array();

$lines = file($file);
foreach($lines as $line){
    $line = trim($line);
    if(empty($line)){
        continue;
    }
    list($item,$code) = preg_split('/\s+/',$line);
    if(empty($item) || empty($code)){
        continue;
    }
    if(count($total) >= 900){
        break;
    }
    $code = substr($code, 2);
    if(!in_array($code,$total)){
        $total[] = $code;
    }
    $is_even = intval(substr($item,-1,1)) % 2 == 0;
    if($is_even && !in_array($code,$even)){
        $even[] = $code;
    }else if(!in_array($code,$odd)){
        $odd[] = $code;
    }
}

file_put_contents($dir . '奇数.txt',implode("\r\n",$odd));
file_put_contents('param.txt',"'奇数期':['" . implode("','", $odd) ."']," . PHP_EOL);
file_put_contents($dir . '偶数.txt',implode("\r\n",$even));
file_put_contents('param.txt',"'偶数期':['" . implode("','", $even) ."']," . PHP_EOL,FILE_APPEND);