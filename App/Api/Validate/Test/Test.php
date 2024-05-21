<?php
namespace App\Api\Validate\Test;
use EasySwoole\Component\CoroutineSingleTon;

class Test
{
    use CoroutineSingleTon;

    private $rules = [
        'test'  => 'lengthMax:100',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
