<?php
use Swoole\Http\Server;

$http = new Server("0.0.0.0", 9502);

$http->set([
	'enable_static_handler'	=> true,
	'document_root'			=> '/www/wwwroot/swooletp5/public/static',
	'worker_num'			=> 5,
]);

$http->on('WorkerStart', function(swoole_server $server, $worker_id){
	define('APP_PATH', __DIR__.'/../application/');
	require __DIR__.'/../thinkphp/base.php';
	// require __DIR__.'/../public/index.php';
});

$http->on('request', function($request, $response) use ($http){
  	//$_SERVER = [];
  //	if(isset($_SERVER)){unset($_SERVER);}
	if(isset($request->server)){
		foreach ($request->server as $key => $value) {
			$_SERVER[strtoupper($key)] = $value;
		}
	}

	if(isset($request->header)){
		foreach ($request->header as $key => $value) {
			$_SERVER[strtoupper($key)] = $value;
		}
	}
	$_GET = [];
	if(isset($request->get)){
		foreach ($request->get as $key => $value) {
			$_GET[$key] = $value;
		}
	}
	$_POST = [];
	if(isset($request->post)){
		foreach ($request->post as $key => $value) {
			$_POST[$key] = $value;
		}
	}

	ob_start();

	try{
		//think\Container::get('app', [APP_PATH])->run()->send();
      	think\Container::get('app')->run()->send();
	}catch(\Exception $e){

	}
	//echo request()->action().PHP_EOL;
	$res = ob_get_contents();
	ob_end_clean();
	$response->end($res);
	//$http->close($response->fd);
});

$http->start();