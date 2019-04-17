<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        dump($_GET);
        return 'leaf'.PHP_EOL;
    }
  
    public function hh()
    {
       return mt_rand(100000,999999);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
