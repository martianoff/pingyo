<?php
namespace PingYo;

class Application {
	
	public $campaign;
	public $affiliateid;
	public $subaffiliate;
	public $timeout;
	public $testonly;
	
	private $applicationdetails;
	private $sourcedetails;
	private $connection_status = false;
	private $logger = null;
	
	private $validation_rules = [
		'required'=>[
			[['affiliateid', 'timeout','applicationdetails','sourcedetails']]
		],
		'integer'=>[
			[['timeout']]
		],
		'in'=>[
			[['testonly'], [false,true]]
		],
		'min'=>[
			[['timeout'], 45]
		],
		'max'=>[
			[['timeout'], 120]
		],
		'instanceOf'=>[
			[['applicationdetails'], 'PingYo\ApplicationDetails'],
			[['sourcedetails'], 'PingYo\SourceDetails'],
		]
	];
	
	public function attachLogger(\Psr\Log\LoggerInterface $logger){
		$this->logger = $logger;
	}
	
	public function validate($full_validation=true) {
		if(!is_null($this->logger))$this->logger->info("validation start");
		
		$validator = new \Valitron\Validator(array('campaign'=>$this->campaign,'affiliateid'=>$this->affiliateid,'subaffiliate'=>$this->subaffiliate,'timeout'=>$this->timeout,'testonly'=>$this->testonly,'applicationdetails'=>$this->applicationdetails,'sourcedetails'=>$this->sourcedetails));
		$validator->rules($this->validation_rules);
		if($validator->validate()) {
			if($full_validation)
			{
				if(($this->applicationdetails->validate())&&($this->sourcedetails->validate()))
				{
					if(!is_null($this->logger))$this->logger->info("validation passed");
					return true;
				}
				else
				{
					if(!is_null($this->logger))$this->logger->warning("validation errors found in child object");
					return false;
				}
			}
			else
		    return true;
		} else {
		    if(!is_null($this->logger))$this->logger->warning("validation errors found in main object: ",array('errors'=>$validator->errors()));
		    return $validator->errors();
		}
	}
	
	public function setApplicationDetails(ApplicationDetails $applicationdetails)
	{
		$this->applicationdetails = $applicationdetails;
	}
	
	public function setSourceDetails(SourceDetails $sourcedetails)
	{
		$this->sourcedetails = $sourcedetails;
	}
	
	public function toArray(){
		$r=$this->validate();
		if($r===true)
		{
			return ['Campaign'=>$this->campaign,'AffiliateId'=>$this->affiliateid,'SubAffiliate'=>$this->subaffiliate,'Timeout'=>$this->timeout,'TestOnly'=>$this->testonly,'Application'=>$this->applicationdetails->toArray(),'SourceDetails'=>$this->sourcedetails->toArray()];
		}
		else
		return false;
	}
	
	public function toJson() {
		$r=$this->validate();
		if($r===true)
		{
			return json_encode($this->toArray());
		}
		else
		return false;
	}
	
	public function get_connection_status(){
		return $this->connection_status;
	}
	
	public function send() {
		$r=$this->validate();
		if($r===true)
		{
			$request = $this->toJson();
			
			if(!is_null($this->logger))$this->logger->info("request sent: ".$request);
			
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,"https://leads.paydayleadprosystem.co.uk/application/submit");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Accept: application/json, text/javascript, *.*',
		    'Content-type: application/json; charset=utf-8'
		    ));
    
			$server_output = curl_exec($ch);
			$info = curl_getinfo($ch);
			
			if(!is_null($this->logger))$this->logger->info("got response with code ".$info['http_code'].': '.$server_output);
			
			curl_close ($ch);
			$this->connection_status = $info;
			return new Status($info['http_code'],$server_output,null,$this->logger);
		}
		else
		return false;
	}
}