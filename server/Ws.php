<?php
use Swoole\WebSocket\Server;
class Ws
{
	CONST HOST = '0.0.0.0';
	CONST PORT = 9502;

	public $ws = null;
	public function __construct()
	{
		$this->ws = new Server(self::HOST, self::PORT);
		$this->ws->set([
			'worker_num'			=> 4,
			'task_worker_num'		=> 4,
			'enable_static_handler'	=> true,
			'document_root'			=> '/www/wwwroot/swooletp5/public/static',
		]);
      	$this->ws->on('open', [$this, 'onOpen']);
		$this->ws->on('message', [$this, 'onMessage']);
		$this->ws->on('workerstart', [$this, 'onWorkerStart']);
		$this->ws->on('request', [$this, 'onRequest']);
		$this->ws->on('task', [$this, 'onTask']);
		$this->ws->on('finish', [$this, 'onFinish']);
		$this->ws->on('close', [$this, 'onClose']);
		$this->ws->start(); 
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
      
       // $_FILES = [];
		if(isset($request->files))
		{
			foreach ($request->files as $key => $value) {
				$_FILES[$key] = $value;
			}
		}

		$_POST = [];
		if(isset($request->post))
		{
			foreach ($request->post as $key => $value) {
				$_POST[$key] = $value;
			}
		}
        $_POST['http_server'] = $this->ws;

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
    
    public function onOpen($ws, $request)
    {
    	var_dump($request->fd);
    }
  
    public function onMessage($ws, $frame)
    {
    	echo "ser-push-msg:{$frame->data}\n";
        $ws->push($frame->fd, "serverpush:".date('Y-m-d H:i:s'));
    }
  
    public function onClose($server, $fd)
	{
		echo "clientid:{$fd}\n";
	}
}

new Ws();
