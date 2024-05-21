<?php
namespace App\Api\Validate\Share;
use EasySwoole\Component\CoroutineSingleTon;

class Get
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'scene'     => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
