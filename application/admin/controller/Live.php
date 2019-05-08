<?php
namespace app\admin\controller;

use think\facade\Log;
use app\common\lib\redis\Predis;

class Live{
	public function push()
    {
        //Log::write($_GET, 'GETPA');
      //  $_POST['http_server']->push(2, 'hello-push-data');
       $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));
      // dump($clients);
       foreach($clients as $fd){
    		$_POST['http_server']->push($fd, 'hello1234');
       }
       return Util::show(config('code.error'), 'login error');
      
    }
}
