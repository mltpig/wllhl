<?php
namespace App\Api\Validate\Cdk;
use EasySwoole\Component\CoroutineSingleTon;

class RedeemCdk
{
    use CoroutineSingleTon;

    private $rules = [
        'cdk'       => 'required|notEmpty',
        'playerid'  => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
    ];

    public function getRules():array
    {
        return $this->rules;
    }
}
