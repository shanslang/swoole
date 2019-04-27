<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Log;
use app\common\lib\Util;

class Image extends Controller
{
	public function index()
    {
         //$hh = json_encode($_FILES);
       $file = request()->file('file');
       $info = $file->move('../public/static/upload');
     //  Log::write($info, 'file');
       if($info){
        	$data = [
            	'image' => config('live.host')."/upload/".$info->getSaveName(),
            ];
           // $ret = Util::show(config('code.success'), 'ok', $data);
           
            //Log::write($ret, 'RT');
            return Util::show(config('code.success'), 'ok', $data);
        }else{
            return Util::show(config('code.error'), 'err');
       }
    }
}