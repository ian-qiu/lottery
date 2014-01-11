<?php

include dirname(__FILE__) . '/../../config/global.php';

include_once(ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php');

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
$s->display('tpl.transform.html');

