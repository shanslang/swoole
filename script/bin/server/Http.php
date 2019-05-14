<?php
use Swoole\Http\Server;
class Http
{
	CONST HOST = '0.0.0.0';
	CONST PORT = 9502;

	public $http = null;
	public function __construct()
	{
		$this->http = new Server(self::HOST, self::PORT);
		$this->http->set([
			'worker_num'			=> 4,
			'task_worker_num'		=> 4,
			'enable_static_handler'	=> true,
			'document_root'			=> '/www/wwwroot/swooletp5/public/static',
		]);
		$this->http->on('workerstart', [$this, 'onWorkerStart']);
		$this->http->on('request', [$this, 'onRequest']);
		$this->http->on('task', [$this, 'onTask']);
		$this->http->on('finish', [$this, 'onFinish']);
		$this->http->on('close', [$this, 'onClose']);
		$this->http->start(); 
	}
  
    public function onWorkerStart($server, $worker_id)
	{
		define('APP_PATH', __DIR__.'/../application/');
		require __DIR__.'/../thinkphp/base.php';
        App::run()->send();
	}
  
    public function onRequest($request, $response)
	{
		//$_SERVER = [];
		if(isset($request->server))
		{
			foreach ($request->server as $key => $value) {
				$_SERVER[strtoupper($key)] = $value;
			}
		}
		if(isset($request->header))
		{
			foreach ($request->header as $key => $value) {
				$_SERVER[strtoupper($key)] = $value;
			}
		}

		$_GET = [];
		if(isset($request->get))
		{
			foreach ($request->get as $key => $value) {
				$_GET[$key] = $value;
			}
		}

		$_POST = [];
		if(isset($request->post))
		{
			foreach ($request->post as $key => $value) {
				$_POST[$key] = $value;
			}
		}
        $_POST['http_server'] = $this->http;

		ob_start();

		try{
           think\Container::get('app')->run()->send();
		}catch(\Exception $e){

		}

		$res = ob_get_contents();
		ob_end_clean();
		$response->end($res);
	}
  
    public function onTask($serv, $taskId, $workerId, $data)
	{
		//$obj = new app\common\lib\ali\Sms();
		//try{
			//$response = $obj::sendSms($data['phone'], $data['code']);
		//}catch(\Exception $e){
			//echo $e->getMessage();
		//}
        
        $obj = new app\common\lib\task\Task;
        $method = $data['method'];
        $flag = $obj->$method($data['data']);
       echo $flag.'taskhhhhh'.PHP_EOL;
		return $flag;
	}
  
    public function onFinish($serv, $taskid, $data)
	{
		echo "taskId:{$taskid}\n";
		echo "finish-data-success:{$data}\n";
	}
  
    public function onClose($server, $fd)
	{
		echo "clientid:{$fd}\n";
	}
}

new Http();
