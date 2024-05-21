<?php
namespace App\Api\Validate\Player;
use EasySwoole\Component\CoroutineSingleTon;

class Set
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
        'avatar'    => 'required|notEmpty|lengthMax:500',
        'name'      => 'required|notEmpty|lengthMax:500',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
