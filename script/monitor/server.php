<?php

class Server
{
	const PORT = 9502;
  	
  	public function port()
    {
       // $shell = "netstat -anp | grep ".self::PORT;
        $shell = "netstat -anp | grep ".self::PORT." | grep LISTEN | wc -l";
        $result = shell_exec($shell);
        if($result != 1){
        	//  就发短信等
           echo date('Ymd H:i:s').'error'.PHP_EOL.$result;
        }else{
        	//  正常就记录日志
          echo date('Ymd H:i:s').'success'.PHP_EOL.$result;
        }
        // echo $result;
    }
}

//(new Server())->port();

swoole_timer_tick(2000, function($timer_id){
	(new Server())->port();
  	echo "time-start".PHP_EOL;
});