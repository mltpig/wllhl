<?php
namespace App\Api\Validate\Player;
use EasySwoole\Component\CoroutineSingleTon;

class SaveWlKeep
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
        'data'      => 'required|notEmpty|lengthMax:30000',
    ];

    public function getRules():array
    {
        return $this->rules;
    }
}
