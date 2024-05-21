<?php
namespace App\Api\Validate\Share;
use EasySwoole\Component\CoroutineSingleTon;

class Set
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'scene'     => 'required|notEmpty',
        'shareuid'  => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
