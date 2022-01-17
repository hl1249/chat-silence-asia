<?php

namespace app\http;

use think\worker\Server;

use Workerman\Lib\Timer;

// 使用db
use think\DB;

class Worker extends Server
{
		
    protected $socket = 'http://0.0.0.0:2345';
	protected $processes = 1;
    static $uidConnections = [];
	static $count = 0;
	
	
    public function onMessage($connection, $message){
        // $person->send($data)
	   // $message_data = json_decode($message, true);
        
        if (!in_array($connection,self::$uidConnections)) {
            array_push(self::$uidConnections,$connection);
        }
        foreach (self::$uidConnections as $person) {
            $message_data;
            
			if($message){
				$message_data = $message;
			}else{
			    $message_data = '{type:"count",count:"' .self::$count.'"}';
			}
			$person->send($message_data);
        }
    }
	
	// 当用户连接时
	public function onConnect($connection){
		self::$count++;
	}
	
	// 当用户关闭时
	public function onClose($connection){
		
		self::$count--;
		foreach (self::$uidConnections as $person) {
		    // $person->send($data)
			$c = array('type'=>'count','count'=>self::$count);
			$person->send(json_encode($c));
		}
	}
	
}