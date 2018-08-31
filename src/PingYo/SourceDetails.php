<?php

namespace PingYo;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function attachLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function toJson()
    {

        $this->logger->debug("SourceDetails::toJson() called");

        $r = $this->validate();
        if ($r === true) {
            return json_encode($this->toArray());
        }
    }

    public function validate()
    {

        $this->logger->debug("SourceDetails::validate() called");

        $validator = new ExtendedValidator(array(
            'address' => $this->address,
            'clientuseragent' => $this->clientuseragent,
            'creationurl' => $this->creationurl,
            'referringurl' => $this->referringurl,
            'languagecodes' => $this->languagecodes
        ));
        $validator->rules($this->validation_rules);
        if ($validator->validate()) {

            $this->logger->info("SourceDetails validation passed");

            return true;
        } else {

            $this->logger->warning("SourceDetails validation errors found: ", array('errors' => $validator->errors()));

            return $validator->errors();
        }
    }

    public function toArray()
    {

        $this->logger->debug("SourceDetails::toArray() called");

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