<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$total = array();
for($i=0;$i<10;$i++){
    for($j=0;$j<10;$j++){
        for($k=0;$k<10;$k++){
            $total[] = strval($i) . strval($j) . strval($k);
        }
    }
}

