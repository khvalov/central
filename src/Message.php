<?php
namespace Central;

use CentralException;

class Message
{
    
    
    private $header;
    private $to;
    private $payload;
	
	private $types=['user','channel']; //List of allowed types



    public function __construct(array $settings=[])
    {
		if(array_key_exists('to',$settings) && array_key_exists('body',$settings)){
			$this->setTo($settings['to']);
			$this->setBody($settings['body']);
		}
       
    }
	
	public function setBody($body){
		
		$this->payload=json_encode($body);
	}


    public function setTo($uuid_string){
			
		if(strpos(":", $uuid_string)!== false){
			list($type,$uuid) = explode(":",$uuid_string);
			
			if(in_array($type,$this->types)){
				$this->to=['type'=>$type,'uuid'=>$uuid];
			} else {
				throw new CentralException('Invalid type'.$type);
			}
		} elseif(strlen($uuid_string==36)){
			
			$this->to=['type'=>"user",'uuid'=>$uuid_string];
			
		} else {
			
			throw new CentralException('Unable to set recipient:'.$uuid_string);
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

    public function getMessage(){
        
        return array('message'=>[
            'header'=>[
                'from'=>[
                    'uuid'=>$this->from
                ],
                'to'=>$this->to,
                "timestamp"=> time()
            ],
            "payload"=> $this->payload
        ]);
    }
        
    
}
