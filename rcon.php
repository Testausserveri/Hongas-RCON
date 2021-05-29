<?php
class Rcon {
	
	private $socket;
	private $auth = false;
	private $RequestId = 0;
	
	const SERVERDATA_EXECCOMMAND = 2;
	const SERVERDATA_AUTH = 3;  
	const SERVERDATA_RESPONSE_VALUE = 0;  
	
	
	
	public function __construct($host, $port, $password, $timeout = 2)
	{
		$this->socket = @fsockopen($host, $port, $errno, $errstr, $timeout);
		
		if (!$this->socket) {
			throw new exception('Error: '. $errstr);
		}
		
		$this->write(self::SERVERDATA_AUTH, $password);
		$buffer = $this->read();
			
		$request = $this->GetLong($buffer);
		$code		= $this->GetLong($buffer);
		
		if ($request == -1 || $code != 2) {
			throw new exception('RCON login failed.');
		}
		
		$this->auth = true;
	
		return true;
	}
}