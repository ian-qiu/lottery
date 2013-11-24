<?php

define('ROOT_DIR', dirname(__FILE__) . '/../');
date_default_timezone_set('Asia/Shanghai');
@ini_set('error_log',ROOT_DIR . 'log/php_errors.log');

class Shishicai{
    const URL = 'http://www.shishicai.cn/cqssc/touzhu/';
}

class DB{
    const HOST = '10.161.78.122';
    const PORT = '3306';
    const USER = 'lottery';
    const PASSWD = 'PJaxfpZg';
}