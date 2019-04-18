<?php
namespace app\index\controller;

use think\Controller;
use think\Validate;
use think\facade\Log;
use app\common\lib\ali\Sms;
use app\common\lib\Util;


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
        try{
            $response = Sms::sendSms($phoneNum, $code);
            Log::write($response, 'Sms');
            return Util::show(config('code.success'), '发送成功');
        }catch (\Exception $e){
            return Util::show(config('code.error'), '阿里内部异常');
        }
	}
}