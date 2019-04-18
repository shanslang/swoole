<?php
namespace app\common\lib;

class Util
{
	public static function show($status, $message = '', $data = [])
    {
    	$result = [
        	'status'	=> $status,
            'msg'	    => $message,
            'data'	    => $data,
        ];
        
       // return json_encode($result);
       return json($result);
    }
}