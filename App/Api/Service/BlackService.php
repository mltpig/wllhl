<?php

namespace App\Api\Service;
use EasySwoole\Component\CoroutineSingleTon;

class BlackService
{
    use CoroutineSingleTon;

    public function blackList($uid)
    {
        $list = [
            'oualn5OCoW4nO6eU2w5Onqb_ZnXw',
            'oualn5FZtLDZS9WbRDOfRWig_WTQ',
            'o7ETU5UcDge6TWgwLQZxniL950Xw',
        ];
        foreach ($list as $k => $v) {
            if($uid == $v) return true;
        }
    }
}
