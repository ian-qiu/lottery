<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Doraemon{
    
    public static $instaces = array();
    
    public function __get($key) {
        if(isset(self::$instaces[$key])){
            return self::$instaces;
        }
        $method_name = 'get' . ucfirst($key);
        if(method_exists($this, $method_name)){
            $value = $this->$method_name();
            self::$instaces[$key] = $value;
            return $value;
        }else{
            throw new Exception("$key not exists!");
        }
    }
    
    /**
     * 
     * @return \ParamHandler
     */
    public static function getParam(){
        require_once ROOT_DIR . 'model/ParamHandler.php';
        return new ParamHandler();
    }
    
    /**
     * get smarty instance
     * 
     * @return Smarty
     */
    public function getSmarty(){
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