<?php

class PingYoSourceDetails {
		
	public $address;
	public $clientuseragent;
	public $creationurl;
	public $referringurl;
	public $languagecodes;
	
	private $validation_rules = [
		'required'=>[
			[['address', 'clientuseragent','creationurl']]
		],
		'array'=>[
			[['languagecodes']]
		],
		'url'=>[
			[['creationurl','referringurl']]
		]
	];
	
	public function validate() {
		$validator = new Valitron\Validator(array('address'=>$this->address,'clientuseragent'=>$this->clientuseragent,'creationurl'=>$this->creationurl,'referringurl'=>$this->referringurl,'languagecodes'=>$this->languagecodes));
		$validator->rules($this->validation_rules);
		if($validator->validate()) {
		    return true;
		} else {
		    return $validator->errors();
		}
	}
	
	public function toArray(){
		$r=$this->validate();
		if($r===true)
		{
			return ['Address'=>$this->address,'ClientUserAgent'=>$this->clientuseragent,'CreationUrl'=>$this->creationurl,'LanguageCodes'=>$this->languagecodes,'ReferringUrl'=>$this->referringurl];
		}
	}
	
	public function toJson() {
		$r=$this->validate();
		if($r===true)
		{
			return json_encode($this->toArray());
		}
	}

}