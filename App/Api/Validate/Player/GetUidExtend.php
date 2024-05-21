<?php
namespace App\Api\Validate\Player;
use EasySwoole\Component\CoroutineSingleTon;

class GetUidExtend
{
    use CoroutineSingleTon;

    private $rules = [
        'uid'       => 'required|notEmpty',
        'timestamp' => 'required|notEmpty|timestamp',
        'sign'      => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
