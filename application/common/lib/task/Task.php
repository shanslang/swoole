<?php

namespace app\common\lib\task;

use think\Controller;
use thinl\facade\Log;
use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;

class Task extends Controller
{
	public function sendSms($data, $serv)
	{
		try{
			$response = Sms::sendSms($data['phone'], $data['code']);
            $response->Code = 'OK';
		}catch(\Exception $e){
			// echo $e->getMessage();
			return false;
		}
		//return $response;

		// 发送成功，把验证码记录到Redis里
		if($response->Code == 'OK')
		{
			Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
		}
		return true;
	}
  
    public function pushLive($data, $serv){
      $clients = Predis::getInstance()->sMembers(config("redis.live_game_key"));
      foreach($clients as $fd){
          $serv->push($fd, json_encode($data));
      }
  }
}