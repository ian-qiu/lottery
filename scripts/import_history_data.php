<?php
@ini_set('display_errors', 'On');
error_reporting(E_ALL);
$dir = dirname(__FILE__) . '/';
$file = $dir . '重庆时时彩A.txt';

if(!is_file($file)){
    die($file . ' not exists!');
}

$handle = fopen($file,'r');
if(!$handle){
    die("can not open " . $file);
}

while(!feof($handle)){
    $line = fgets($handle,1024);
    if(!$line){
        continue;
    }
    $line = trim($line);
    if(!$line){
        continue;
    }
    list($item_date,$item_code) = preg_split('/\s+/',$line);
    $sql = 'insert into shishicai(item_date,item_code)values';
    $sql .= sprintf('("%s","%s")',$item_date,$item_code);
    $sql .= 'on duplicate key update item_code=values(item_code);';
    echo $sql . PHP_EOL;
}
fclose($handle);