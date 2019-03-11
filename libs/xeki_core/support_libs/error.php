<?php
namespace xeki;

class error{
    public $code;
    public $name;

    function  __construct($code="",$name=""){
        $this->code= $code;
        $this->name= $name;

    }
}