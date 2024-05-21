<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/25
 * Time: 下午4:10
 */

namespace EasySwoole\Socket\AbstractInterface;


use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;
use EasySwoole\Socket\Client\Tcp;
use EasySwoole\Socket\Client\Udp;
use EasySwoole\Socket\Client\WebSocket;
use Swoole\Server;

abstract class Controller
{
    private $response;
    private $caller;
    private $server;

    private $allowMethods = [];

    function __construct()
    {
        //支持在子类控制器中以private，protected来修饰某个方法不可见
        $list = [];
        $ref = new \ReflectionClass(static::class);
        $public = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($public as $item){
            array_push($list,$item->getName());
        }
        $this->allowMethods = array_diff($list,
            [
                '__hook','__destruct',
                '__clone','__construct','__call',
                '__callStatic','__get','__set',
                '__isset','__unset','__sleep',
                '__wakeup','__toString','__invoke',
                '__set_state','__clone','__debugInfo'
            ]
        );
    }

    protected function actionNotFound(?string $actionName)
    {

    }

    protected function afterAction(?string $actionName)
    {

    }

    protected function onException(\Throwable $throwable):void
    {
        throw $throwable;
    }

    /*
     * 返回false的时候为拦截
     */
    protected function onRequest(?string $actionName):bool
    {
        return true;
    }

    protected function response():Response
    {
        return $this->response;
    }

    protected function responseImmediately(string $string)
    {
        $client = $this->caller->getClient();
        if($client instanceof WebSocket){
            $this->server->push($client->getFd(),$string);
        }else if($client instanceof Tcp){
            $this->server->send($client->getFd(),$string);
        }else if($client instanceof Udp){
            $this->server->sendto($client->getAddress(),$client->getPort(),$string,$client->getServerSocket());
        }
    }

    protected function caller():Caller
    {
        return $this->caller;
    }

    public function __hook(Server $server,Caller $request, Response $response)
    {
        $this->caller = $request;
        $this->response = $response;
        $this->server = $server;
        $actionName = $request->getAction();
        try{
            if($this->onRequest($actionName) !== false){
                if(in_array($actionName,$this->allowMethods)){
                    $this->$actionName();
                }else{
                    $this->actionNotFound($actionName);
                }
            }
        }catch (\Throwable $throwable){
            //若没有重构onException，直接抛出给上层
            $this->onException($throwable);
        }finally{
            try{
                $this->afterAction($actionName);
            }catch (\Throwable $throwable){
                $this->onException($throwable);
            }
        }
    }
}