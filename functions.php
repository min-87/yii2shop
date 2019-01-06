<?php
//в красивой форме распечатывает то, что передаётся параметром
function debug($arr){
    echo '<pre>' . print_r($arr, true) . '</pre>';
}