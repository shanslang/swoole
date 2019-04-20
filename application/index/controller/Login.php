<?php
namespace app\index\controller;

use think\facade\Validate;
use think\facade\Log;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use app\common\lib\Util;


class Login
{
	public function index()
	{   
		$phoneNum = intval($_POST['phone_num']);
		$code 	  = intval($_POST['code']);
        $validate = Validate::make([
      		'phone_num'   => 'require|mobile',
            'code'		  => 'require|number',
        ]);
        if(!$validate->check($_POST))
        {
        	$msg = $phoneNum.'='.$validate->getError();
            return Util::show(config('code.error'), $msg);
        }
      
		try{
			$redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));
		}catch(\Exception $e){
			$msg = $phoneNum.'='.$e->getMessage();
            return Util::show(config('code.error'), $msg);
		}

		if($redisCode == $code){
			$data = [
				'user'    => $phoneNum,
				'srcKey'  => md5(Redis::userkey($phoneNum)),
				'time'    => time(),
				'isLogin' => true,
			];
			Predis::getInstance()->set(Redis::userkey($phoneNum), $data);
            return Util::show(config('code.success'), 'ok', $data);
		}else{
        	return Util::show(config('code.error'), 'login error');
        }
	}
}