<?php
namespace App\Api\Validate\Rank;
use EasySwoole\Component\CoroutineSingleTon;

class Get
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
        'rankenum'  => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
