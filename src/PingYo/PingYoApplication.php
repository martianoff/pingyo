<?php

class PingYoApplication {
	
	public $campaign;
	public $affiliateid;
	public $subaffiliate;
	public $timeout;
	public $testonly;
	private $applicationdetails;
	private $sourcedetails;
	
	private $connection_status = false;
	
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
			[['applicationdetails'], 'PingYoApplicationDetails'],
			[['sourcedetails'], 'PingYoSourceDetails'],
		]
	];
	
	public function validate($full_validation=true) {
		$validator = new Valitron\Validator(array('campaign'=>$this->campaign,'affiliateid'=>$this->affiliateid,'subaffiliate'=>$this->subaffiliate,'timeout'=>$this->timeout,'testonly'=>$this->testonly,'applicationdetails'=>$this->applicationdetails,'sourcedetails'=>$this->sourcedetails));
		$validator->rules($this->validation_rules);
		if($validator->validate()) {
			if($full_validation)
			{
				if(($this->applicationdetails->validate())&&($this->sourcedetails->validate()))
				return true;
				else
				return false;
			}
			else
		    return true;
		} else {
		    return $validator->errors();
		}
	}
	
	public function setApplicationDetails(PingYoApplicationDetails $applicationdetails)
	{
		$this->applicationdetails = $applicationdetails;
	}
	
	public function setSourceDetails(PingYoSourceDetails $sourcedetails)
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
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,"https://leads.paydayleadprosystem.co.uk/application/submit");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$this->toJson());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Accept: application/json, text/javascript, *.*',
		    'Content-type: application/json; charset=utf-8'
		    ));
    
			$server_output = curl_exec ($ch);
			$info = curl_getinfo($ch);
			curl_close ($ch);
			
			$this->connection_status = $info;			
			return new PingYoStatus($info['http_code'],$server_output);
		}
		else
		return false;
	}
}