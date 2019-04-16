<?php
$http = new swoole_http_server('0.0.0.0', 9501);

$http->set([
	'enable_static_handler'	=> true,
	'document_root'			=> '/www/wwwroot/swooletp5/public/static',
	'worker_num'			=> 5,
]);

$http->on('WorkerStart', function(swoole_server $server, $worker_id){
	define('APP_PATH', __DIR__.'/../application/');
	require __DIR__.'/../thinkphp/base.php';
});

$http->on('request', function($request, $response){
	$response->end('请求');
});

$http->start();