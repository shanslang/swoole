<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        dump($_GET);
        return 'leaft';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
