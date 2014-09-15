<?php
namespace PingYo;

class SourceDetails
{

    public $address;
    public $clientuseragent;
    public $creationurl;
    public $referringurl;
    public $languagecodes;

    private $logger = null;

    private $validation_rules = [
        'required' => [
            [['address', 'clientuseragent', 'creationurl']]
        ],
        'array' => [
            [['languagecodes']]
        ],
        'url' => [
            [['creationurl', 'referringurl']]
        ]
    ];

    public function attachLogger(\Psr\Log\LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function toJson()
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("SourceDetails::toJson() called");
        }
        $r = $this->validate();
        if ($r === true) {
            return json_encode($this->toArray());
        }
    }

    public function validate()
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("SourceDetails::validate() called");
        }
        $validator = new ExtendedValidator(array(
                'address' => $this->address,
                'clientuseragent' => $this->clientuseragent,
                'creationurl' => $this->creationurl,
                'referringurl' => $this->referringurl,
                'languagecodes' => $this->languagecodes
            ));
        $validator->rules($this->validation_rules);
        if ($validator->validate()) {
            if (!is_null($this->logger)) {
                $this->logger->info("SourceDetails validation passed");
            }
            return true;
        } else {
            if (!is_null($this->logger)) {
                $this->logger->warning("SourceDetails validation errors found: ",
                    array('errors' => $validator->errors()));
            }
            return $validator->errors();
        }
    }

    public function toArray()
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("SourceDetails::toArray() called");
        }
        $r = $this->validate();
        if ($r === true) {
            return [
                'Address' => $this->address,
                'ClientUserAgent' => $this->clientuseragent,
                'CreationUrl' => $this->creationurl,
                'LanguageCodes' => $this->languagecodes,
                'ReferringUrl' => $this->referringurl
            ];
        }
    }

}