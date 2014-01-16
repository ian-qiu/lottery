<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ParamHandler{
    public function get($key,$default = ''){
        if(isset($_REQUEST[$key])){
            $value = $_REQUEST[$key];
        }else{
            $value = $default;
        }
        return $value;
    }
}