<?php

namespace PingYo;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Application
{
    public $campaign;
    public $affiliateid;
    public $subaffiliate;
    public $timeout;
    public $testonly;

    /**
     * @var ApplicationDetails
     */
    private $applicationdetails;

    /**
     * @var SourceDetails
     */
    private $sourcedetails;

    /**
     * @var bool
     */
    private $connection_status = false;

    /**
     * @var LoggerInterface|null
     */
    private $logger = null;

    private $validation_rules = [
        'required' => [
            [['affiliateid', 'timeout', 'applicationdetails', 'sourcedetails']]
        ],
        'integer' => [
            [['timeout']]
        ],
        'in' => [
            [['testonly'], [false, true]]
        ],
        'min' => [
            [['timeout'], 45]
        ],
        'max' => [
            [['timeout'], 120]
        ],
        'instanceOf' => [
            [['applicationdetails'], 'PingYo\ApplicationDetails'],
            [['sourcedetails'], 'PingYo\SourceDetails'],
        ]
    ];

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    public function attachLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setApplicationDetails(ApplicationDetails $applicationdetails)
    {
        $this->logger->debug("Application::setApplicationDetails() called with applicationdetails=" . var_export($applicationdetails,
                true));

        $this->applicationdetails = $applicationdetails;

        $applicationdetails->attachLogger($this->logger);

    }

    public function setSourceDetails(SourceDetails $sourcedetails)
    {
        $this->logger->debug("Application::setSourceDetails() called with sourcedetails=" . var_export($sourcedetails,
                true));

        $this->sourcedetails = $sourcedetails;

        $sourcedetails->attachLogger($this->logger);

    }

    public function get_connection_status()
    {
        $this->logger->debug("Application::get_connection_status() called");

        return $this->connection_status;
    }

    public function send()
    {
        $this->logger->debug("Application::send() called");

        $r = $this->validate();
        if ($r === true) {
            $request = $this->toJson();
            $this->logger->info("request sent: " . $request);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://leads.pingyo.co.uk/application/submit');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json, text/javascript, *.*',
                'Content-type: application/json; charset=utf-8'
            ));

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);


            $this->logger->info("got response with code " . $info['http_code'] . ': ' . $server_output);


            curl_close($ch);
            $this->connection_status = $info;
            return new Status($info['http_code'], $server_output, null, $this->logger);
        }

        return false;
    }

    public function validate($full_validation = true)
    {
        $this->logger->debug("Application::validate() called with full_validation=$full_validation");
        $validator = new ExtendedValidator(array(
            'campaign' => $this->campaign,
            'affiliateid' => $this->affiliateid,
            'subaffiliate' => $this->subaffiliate,
            'timeout' => $this->timeout,
            'testonly' => $this->testonly,
            'applicationdetails' => $this->applicationdetails,
            'sourcedetails' => $this->sourcedetails
        ));
        $validator->rules($this->validation_rules);
        if ($validator->validate()) {
            if ($full_validation) {
                if (($this->applicationdetails->validate()) && ($this->sourcedetails->validate())) {

                    $this->logger->info("Application validation passed");

                    return true;
                }
                $this->logger->warning("Application validation errors found in child object");

                return false;
            }

            return true;
        }
        $this->logger->warning("Application validation errors found in main object: ",
            array('errors' => $validator->errors()));

        return $validator->errors();
    }

    public function toJson()
    {
        $this->logger->debug("Application::toJson() called");

        $r = $this->validate();
        if ($r === true) {
            return json_encode($this->toArray());
        }

        return false;
    }

    public function toArray()
    {
        $this->logger->debug("Application::toArray() called");

        $r = $this->validate();
        if ($r === true) {
            return [
                'Campaign' => $this->campaign,
                'AffiliateId' => $this->affiliateid,
                'SubAffiliate' => $this->subaffiliate,
                'Timeout' => $this->timeout,
                'TestOnly' => $this->testonly,
                'Application' => $this->applicationdetails->toArray(),
                'SourceDetails' => $this->sourcedetails->toArray()
            ];
        }

        return false;
    }
}