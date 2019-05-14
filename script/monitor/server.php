<?php

class Server
{
	const PORT = 9502;
  	
  	public function port()
    {
       // $shell = "netstat -anp | grep ".self::PORT;
        $shell = "netstat -anp | grep ".self::PORT." | grep LISTEN | wc -l";
        $result = shell_exec($shell);
        if($result = 0){
        	//  就发短信等
        }else{
        	//  正常就记录日志
        }
        // echo $result;
    }
}

(new Server())->port();