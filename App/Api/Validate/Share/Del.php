<?php
namespace App\Api\Validate\Share;
use EasySwoole\Component\CoroutineSingleTon;

class Del
{
    use CoroutineSingleTon;

    private $rules = [
        'playerid'  => 'required|notEmpty',
        'scene'     => 'required|notEmpty',
        'uid'       => 'required|notEmpty',
    ];
    
    public function getRules():array
    {
        return $this->rules;
    }
}
