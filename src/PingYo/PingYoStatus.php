<?php

class PingYoStatus {

	public $httpcode = "";
	public $errors = "";
	public $correlationid = "";
	public $message = "";
	public $statuscheckurl = "";
	
	
	public $percentagecomplete = 0;
	public $redirectionurl = "";
	public $status = "";
	
	function __construct($http_code,$json_response){
		$r = json_decode($json_response);
		$this->httpcode=$http_code;
		if(isset($r->Errors))
		$this->errors=$r->Errors;
		if(isset($r->CorrelationId))
		$this->correlationid=$r->CorrelationId;
		if(isset($r->Message))
		$this->message=$r->Message;
		if(isset($r->StatusCheckUrl))
		$this->statuscheckurl=$r->StatusCheckUrl;
	}
	
	public function refresh(){
		$ch = curl_init();
				
		curl_setopt($ch, CURLOPT_URL,"https://leads.paydayleadprosystem.co.uk".$this->statuscheckurl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Accept: application/json, text/javascript, *.*',
	    'Content-type: application/json; charset=utf-8'
	    ));

		$server_output = curl_exec ($ch);
		
		$r = json_decode($server_output);
		if(isset($r->PercentageComplete))
		$this->percentagecomplete=$r->PercentageComplete;
		if(isset($r->RedirectionUrl))
		$this->redirectionurl=$r->RedirectionUrl;
		if(isset($r->Message))
		$this->message=$r->Message;
		if(isset($r->Status))
		$this->status=$r->Status;
		
		$info = curl_getinfo($ch);
		curl_close ($ch);
		
		return $r;
	}

}