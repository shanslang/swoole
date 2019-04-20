<?php
namespace app\common\lib\redis;

use think\facade\Log;

class Predis
{
	public $redis = '';
	private static $_instance = null;
  
  	public static function getInstance()
	{
		if(empty(self::$_instance))
		{
			self::$_instance = new self();
		}
        return self::$_instance;
	}
	
	private function __construct()
	{
		$this->redis = new \Redis();
        try{
			$this->redis->connect(config('redis.host'), config('redis.port'), config('redis.timeOut'));
		}catch(\Exception $e){
			$msg = $e->getMessage();
            Log::write($msg, 'RedisConn');
		}
        //Log::write('hhh', 'RedisConn2');
	}
  
    public function set($key, $value, $time = 0)
	{
		if(!$key)
		{
			return '';
		}
		if(is_array($value))
		{
			$value = json_encode($value);  // Redis不能存数组，得转为json
		}
		if(!$time)
		{
			return $this->redis->set($key, $value);
		}
		return $this->redis->setex($key, $time, $value);
	}

	public function get($key)
	{
		if(!$key)
		{
			return '';
		}
		return $this->redis->get($key);
	}
}