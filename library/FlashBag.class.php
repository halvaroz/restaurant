<?php

class FlashBag{
    public function __construct(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }

        if(array_key_exists('flash-bag', $_SESSION) == false){
            $_SESSION['flash-bag'] = array();
        }
    }

    public function add($message){
        array_push($_SESSION['flash-bag'], $message);
    }

    public function fetchMessage(){
        return array_shift($_SESSION['flash-bag']);
    }

    public function fetchMessages(){
        $messages = $_SESSION['flash-bag'];
        $_SESSION['flash-bag'] = array();

        return $messages;
    }

    public function hasMessages(){
        return empty($_SESSION['flash-bag']) == false;
    }
}