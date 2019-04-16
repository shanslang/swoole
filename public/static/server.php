<?php
use Swoole\Http\Server;

$http = new Server("0.0.0.0", 9501);

$http->set([
	'document_root' 		=>   '/www/wwwroot/swooletp5/public/static',
	'enable_static_handle'  => 	 true,
	'worker_num'			=>   5,
]);

//$http->on('WorkerStart', function($server, $worker_id){
	///define('APP_PATH', __DIR__. '/../application/');
	//require __DIR__.'/../thinkphp/base.php';
//});

$http->on('request', function($request, $response){
	$response->end("<h1>Hello Swoole. #".rand(1000,9999)."</h1>");
});
$http->start();