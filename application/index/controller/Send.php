<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;
use think\facade\Log;
use app\common\lib\ali\Sms;
use app\common\lib\Util;
use app\common\lib\Redis;


class Send extends Controller
{
	/**
	*发送验证码
	*/
	public function index2()
	{
		$code = mt_rand(100000,999999);
		$phoneNum = $this->request->get('phone_num');
		$validate = new Validate([
			'phoneNum' => 'require|mobile',
		]);
		$data['phoneNum'] = $phoneNum;
        $info['msg'] = '发送成功';
      	$info['status'] = 0;
		if(!$validate->check($data))
		{
			$info['msg'] = $validate->getError();
            $info['status'] = 1;
		}
		Log::write($info['msg'], 'MSG');
        return json($info);
	}
  
    public function index()
	{
		$phoneNum = request()->get('phone_num', 0, 'intval');
       // $phoneNum = $this->request->get('phone_num');
       	//return Util::show(config('code.error'), $phoneNum);
		$validate = new Validate([
			'phoneNum' => 'require|mobile',
		]);
		$data['phoneNum'] = $phoneNum;
		if(!$validate->check($data))
		{
			 $msg = $validate->getError();
             return Util::show(config('code.error'),$msg);
		}
        $code = mt_rand(100000,999999);
        
        $taskData = [
            'method' => 'sendSms',
            'data'   => [
				'phone' => $phoneNum,
				'code'  => $code,
            ]
		];
        $_POST['http_server']->task($taskData);
        return Util::show(config('code.success'), '已发送');
        //try{
            //$response = Sms::sendSms($phoneNum, $code);
           // Log::write($response, 'Sms');
       // }catch (\Exception $e){
           // return Util::show(config('code.error'), '阿里内部异常');
       // }
        
      	// if($response->Code == 'OK')
  //       {
  //           $redis = new \Swoole\Coroutine\Redis();
  //           $redis->connect(config('redis.host'),config('redis.port'));
  //           $redis->set(Redis::smsKey($phoneNum), $code, config('redis.out_time'));
  //           return Util::show(config('code.success'), '发送成功');
  //       }else{
  //       	return Util::show(config('code.error'), '发送失败');
  //       }
	}
  

}