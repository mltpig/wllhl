<?php
namespace App\Api\Validate\Rank;
use EasySwoole\Component\CoroutineSingleTon;

class Set
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
        'rankenum'  => 'required|notEmpty',
        'score'     => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
