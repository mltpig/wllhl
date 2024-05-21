<?php
namespace App\Api\Validate;
use EasySwoole\Component\CoroutineSingleTon;

class ClassPath
{
    use CoroutineSingleTon;

    private $classList = array(
        "test"                  => "\\App\\Api\\Validate\\Test\\Test",

        // "byteCode"               => "\\App\\Api\\Validate\\Channel\\Channel",
        
        "byteUid"               => "\\App\\Api\\Validate\\Player\\Login",

        "saveProfile"           => "\\App\\Api\\Validate\\Player\\Save",
        "saveTfKeep"            => "\\App\\Api\\Validate\\Player\\SaveTfKeep",
        "saveWlKeep"            => "\\App\\Api\\Validate\\Player\\SaveWlKeep",
        "saveKnapsack"          => "\\App\\Api\\Validate\\Player\\SaveKnapsack",

        "getProfile"            => "\\App\\Api\\Validate\\Player\\Get",
        "setAvatarAndNickname"  => "\\App\\Api\\Validate\\Player\\Set",
        "getUidExtend"          => "\\App\\Api\\Validate\\Player\\GetUidExtend",

        // "getProvince"           => "\\App\\Api\\Validate\\Player\\GetProvince",
        "setProvince"           => "\\App\\Api\\Validate\\Player\\SetProvince",

        "getLeaderboard"        => "\\App\\Api\\Validate\\Rank\\Get",
        "setLeaderboard"        => "\\App\\Api\\Validate\\Rank\\Set",

        "getCustomLeaderboard"  => "\\App\\Api\\Validate\\Rank\\GetCustom",
        "setCustomLeaderboard"  => "\\App\\Api\\Validate\\Rank\\SetCustom",

        "invitation"            => "\\App\\Api\\Validate\\Share\\Set",
        "invitationOnFriend"    => "\\App\\Api\\Validate\\Share\\Get",
        "invitationDel"         => "\\App\\Api\\Validate\\Share\\Del",

        "redeemCdk"             => "\\App\\Api\\Validate\\Cdk\\RedeemCdk",
    );

    public function getPath(string $event):string
    {
        return array_key_exists($event,$this->classList)? $this->classList[$event] :'';
    }
}
