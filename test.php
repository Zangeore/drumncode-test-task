<?php
class Test
{
    public function __construct(protected $a, protected  $b = '')
    {
    }
}

var_dump(new Test(...['a' => 'xui', 'c' =>  'asdas']));
