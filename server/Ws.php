<?php
use Swoole\WebSocket\Server;
class Ws
{
	CONST HOST = '0.0.0.0';
	CONST PORT = 9502;
    CONST CHART_PORT = 9503;

	public $ws = null;
	public function __construct()
	{
      	//  删除redis的fd
      //删除代码
		$this->ws = new Server(self::HOST, self::PORT);
        $this->ws->listen(self::HOST, self::CHART_PORT, SWOOLE_SOCK_TCP);
		$this->ws->set([
			'worker_num'			=> 4,
			'task_worker_num'		=> 4,
			'enable_static_handler'	=> true,
			'document_root'			=> '/www/wwwroot/swooletp5/public/static',
		]);
      
        $this->ws->on('start', [$this, 'onStart']);
      	$this->ws->on('open', [$this, 'onOpen']);
		$this->ws->on('message', [$this, 'onMessage']);
		$this->ws->on('workerstart', [$this, 'onWorkerStart']);
		$this->ws->on('request', [$this, 'onRequest']);
		$this->ws->on('task', [$this, 'onTask']);
		$this->ws->on('finish', [$this, 'onFinish']);
		$this->ws->on('close', [$this, 'onClose']);
		$this->ws->start(); 
	}
  
    public function onStart($server)
    {
    	swoole_set_process_name('live_master');  // 修改进程名
    }
  
    public function onWorkerStart($server, $worker_id)
	{
		define('APP_PATH', __DIR__.'/../application/');
		require __DIR__.'/../thinkphp/base.php';
        App::run()->send();
	}
  
    public function onRequest($request, $response)
	{
        //print_r($request->server);  // 会打印出两条请求信息
      	if($request->server['request_uri'] == '/favicon.ico')
        {
        	$response->status(404);
            $response->end();
            return;
        }
      
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
        $this->writeLog();
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
        $flag = $obj->$method($data['data'], $serv);
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
       //print_r($ws);
      	\app\common\lib\redis\Predis::getInstance()->sAdd(config('redis.live_game_key'), $request->fd);
    	var_dump($request->fd);
    }
  
    public function onMessage($ws, $frame)
    {
    	echo "ser-push-msg:{$frame->data}\n";
       // $ws->push($frame->fd, "serverpush:".date('Y-m-d H:i:s'));
    }
  
    public function onClose($server, $fd)
	{
        \app\common\lib\redis\Predis::getInstance()->sRem(config('redis.live_game_key'), $fd);
		echo "clientid:{$fd}\n";
	}
  
    public function writeLog()
    {
    	$datas = array_merge(['date' => date('Ymd H:i:s')], $_GET, $_POST);
        $logs = "";
        foreach($datas as $key => $value)
        {
          	$logs .= $key . ':' . $value." ";
            //echo $value;
        }
        //echo $logs;
        swoole_async_writefile(APP_PATH.'../runtime/log/'.date('Ym')."/".date("d")."_access.log", $logs.PHP_EOL, function($filename){
        	
        }, FILE_APPEND);
    }
}

new Ws();
