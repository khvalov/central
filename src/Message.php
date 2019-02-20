<?php
namespace Central;

use Central\CentralException;

class Message
{
    
    private $header;
    private $to;
	private $from;
    private $payload;
	
	private $types=['user','channel']; //List of allowed types


    public function __construct(array $settings=[])
    {
		if(array_key_exists('to',$settings)){
			$this->setTo($settings['to']);
		}
		if(array_key_exists('from',$settings)){
			$this->setFrom($settings['from']);
		}
		if(array_key_exists('body',$settings)){
			$this->setBody($settings['body']);
		}
       
    }
	
	public function setBody($body){
		
		$this->payload=json_encode($body);
	}
	
	
	public function setFrom($uuid_string){
			
		if(strpos($uuid_string,":")!== false){
			list($type,$uuid) = explode(":",$uuid_string);
			
			if(in_array($type,$this->types)){
				$this->from=['type'=>$type,'uuid'=>$uuid];
			} else {
				throw new CentralException('Invalid type: '.$type);
			}
		} elseif(strlen($uuid_string==36)){
			
			$this->from=['type'=>"user",'uuid'=>$uuid_string];
			
		} else {
			
			throw new CentralException('Unable to set recipient: '.$uuid_string);
		}
        
    }


    public function setTo($uuid_string){
			
		if(strpos($uuid_string,":")!== false){
			list($type,$uuid) = explode(":",$uuid_string);
			
			if(in_array($type,$this->types)){
				$this->to=['type'=>$type,'uuid'=>$uuid];
			} else {
				throw new CentralException('Invalid type: '.$type);
			}
		} elseif(strlen($uuid_string==36)){
			
			$this->to=['type'=>"user",'uuid'=>$uuid_string];
			
		} else {
			
			throw new CentralException('Unable to set recipient: '.$uuid_string);
		}
        
    }
	
	public function getRecipientUUID(){
		return $this->to['uuid'];
	}
	
	public function getRecepientType(){
		return $this->to['type'];
	}

    public function getTo(){
        return $this->to;
    }
	
	public function getFrom(){
		return $this->from;
	}

    public function getMessage(){
        
        return array('message'=>[
            'header'=>[
                'from'=>$this->getFrom(),
                'to'=>$this->getTo(),
                "timestamp"=> time()
            ],
            "payload"=> $this->payload
        ]);
    }
        
    
}
