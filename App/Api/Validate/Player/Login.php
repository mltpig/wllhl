<?php
namespace App\Api\Validate\Player;
use EasySwoole\Component\CoroutineSingleTon;

class Login
{
    use CoroutineSingleTon;

    private $rules = [
        'uid'     => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
