<?php
namespace App\Api\Validate\Rank;
use EasySwoole\Component\CoroutineSingleTon;

class GetCustom
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
        'rankname'  => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
