<?php
namespace Central;

use Central\Message;
use Central\CentralException;
use yii\httpclient\Client;


Class Central {

	private $message;
	private $httpClient;
	private $endpoint;
	private $apikey;
	
	public function __construct($endpoint,$apikey){
		$this->endpoint=$endpoint;
		$this->apikey=$apikey;

		$this->httpClient = new Client(['baseUrl' => $endpoint]);
	}

	public function setMessage(Message $message){
		$this->message=$message;
	}
	
	private function getMessage(){
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
	

	public function publish($message=null){
		
		if(!empty($message) && $message instanceOf Message){
			$this->setMessage($message);
		}
		try{
			$data=[
					"method"=>"publish",
					    "params"=> [
					        "channel": $this->getRcpt(), 
					        "data"=>$this->message->getMessage()
					    ]
					];

			$response = $this->httpClient->createRequest()
						    ->setMethod('POST')
						    ->setFormat(Client::FORMAT_JSON)
						    ->addHeaders(['X-Api-Key' => $this->apikey])
						    ->setData($data)
						    ->send();
		
		} catch (exception $e) {
			
			throw new CentralException($e->getMessage);
		}
	}
	
}