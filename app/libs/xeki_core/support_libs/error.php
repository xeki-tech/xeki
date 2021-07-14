<?php
namespace xeki;

class error{
    public $code;
    public $name;
    public $data;

    function  __construct($code="",$name="",$data=[]){
        $this->code= $code;
        $this->name= $name;
        $this->data= $data;

    }
}