<?php 

if(!function_exists('money_format')) {
    function money_format($amount) {
        return  $amount . ' EGP';
    }
}