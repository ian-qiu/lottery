<?php
@ini_set('display_errors', 'On');
error_reporting(E_ALL);
$dir = '/Users/ian/Downloads/';
$file = $dir . '3000.txt';

if(!is_file($file)){
    die($file . ' not exists!');
}

$odd = array();
$even = array();

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
    $code = substr($code, 2);
    $is_even = intval(substr($item,-1,1)) % 2 == 0;
    if($is_even && !in_array($code,$even) && count($even) < 500){
        $even[] = $code;
    }else if(!in_array($code,$odd) && count($odd) < 500){
        $odd[] = $code;
    }
    if(count($even) + count($odd) == 1000){
        break;
    }
}

file_put_contents($dir . '奇数.txt',implode(PHP_EOL,$odd));
echo "'" . implode("','", $odd) ."'". PHP_EOL;
file_put_contents($dir . '偶数.txt',implode(PHP_EOL,$even));
echo "'" . implode("','", $even) ."'". PHP_EOL;