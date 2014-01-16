<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BaseController{
    
    protected $output = array();
    
    protected function getParam($key,$default = ""){
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
    }

    public function display($template){
        $s = Doraemon::getSmarty();
        if(!empty($this->output)){
            foreach ($this->output as $key => $value) {
                $s->assign($key,$value);
            }
        }
        $s->display($template);
    }
}