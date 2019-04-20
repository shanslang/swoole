<?php
namespace app\common\lib;

class Redis
{
	public static $pre = 'sms_';
    public static $userpre = 'code_';

	/**
	 *存储验证码
	 */
	public static function smsKey($phone)
	{
		return self::$pre.$phone;
	}
	
    public static function userkey($phone){
		return self::$userpre.$phone;
	}
}