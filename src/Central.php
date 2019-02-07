<?php
namespace Central;

use Message;
use CentralException;
use Centrifugo\Centrifugo;
use Centrifugo\Clients\HttpClient;

Class Central {

	private $message;
	private $centrifugo;
	
	public function __construct($endpoint,$apikey){
		$this->centrifugo = new Centrifugo($endpoint, $apikey, new HttpClient());
	}

	public function setMesasge($message Message){
		$this->message=$message;
	}
	
	public function getMessage(){
		return $this->message;
	}
	
	public function getRcpt(){
		switch($this->message->getRecepientType()){
			default:
			case "user":
				$prefix="#";
			break;
			
			case "channel":
				$prefix="";
			break;
		}
		
		return $prefix.$this->message->getRecipientUUID();
	}
	

	public function publish($message Message){
		
		$this->setMessage($message);
		
		$this->centrifugo->publish($this->getRcpt(),$this->getMessage);
	}
	
}