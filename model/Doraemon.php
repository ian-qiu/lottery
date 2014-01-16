<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Doraemon{
    
    /**
     * get smarty instance
     * 
     * @return Smarty
     */
    public static function getSmarty(){
        include_once ROOT_DIR . 'libs/Smarty-3.1.15/libs/Smarty.class.php';
        $s = new Smarty();
        $smarty_options = array(
            'left_delimiter' => '{{',
            'right_delimiter' => '}}',
            'template_dir' => ROOT_DIR . 'website/templates',
            'compile_dir' => ROOT_DIR . 'website/compile',
        );
        foreach ($smarty_options as $k => $v){
            $s->$k = $v;
        }
        return $s;
    }
}