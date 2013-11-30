<?php

$file = '/Users/ian/Downloads/3000.txt';

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
    if(in_array($code,$odd) || in_array($code,$even)){
        continue;
    }
    $is_even = intval(substr($item,-1,1)) % 2 == 0;
    if($is_even){
        $even[] = $code;
    }else{
        $odd[] = $code;
    }
    if(count($even) + count($odd) == 900){
        break;
    }
}

echo "'" . implode("','", $even) ."'". PHP_EOL;
echo "'" . implode("','", $odd) ."'". PHP_EOL;