<?php
namespace PingYo;

class Application
{

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

    public function attachLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setApplicationDetails(ApplicationDetails $applicationdetails)
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("Application::setApplicationDetails() called with applicationdetails=" . var_export($applicationdetails,
                    true));
        }
        $this->applicationdetails = $applicationdetails;
        if (!is_null($this->logger)) {
            $applicationdetails->attachLogger($this->logger);
        }
    }

    public function setSourceDetails(SourceDetails $sourcedetails)
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("Application::setSourceDetails() called with sourcedetails=" . var_export($sourcedetails,
                    true));
        }
        $this->sourcedetails = $sourcedetails;
        if (!is_null($this->logger)) {
            $sourcedetails->attachLogger($this->logger);
        }
    }

    public function get_connection_status()
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("Application::get_connection_status() called");
        }
        return $this->connection_status;
    }

    public function send()
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("Application::send() called");
        }
        $r = $this->validate();
        if ($r === true) {
            $request = $this->toJson();

            if (!is_null($this->logger)) {
                $this->logger->info("request sent: " . $request);
            }

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://leads.paydayleadprosystem.co.uk/application/submit");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json, text/javascript, *.*',
                'Content-type: application/json; charset=utf-8'
            ));

            $server_output = curl_exec($ch);
            $info = curl_getinfo($ch);

            if (!is_null($this->logger)) {
                $this->logger->info("got response with code " . $info['http_code'] . ': ' . $server_output);
            }

            curl_close($ch);
            $this->connection_status = $info;
            return new Status($info['http_code'], $server_output, null, $this->logger);
        } else {
            return false;
        }
    }

    public function validate($full_validation = true)
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("Application::validate() called with full_validation=$full_validation");
        }

        $validator = new \Valitron\Validator(array(
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
                    if (!is_null($this->logger)) {
                        $this->logger->info("Application validation passed");
                    }
                    return true;
                } else {
                    if (!is_null($this->logger)) {
                        $this->logger->warning("Application validation errors found in child object");
                    }
                    return false;
                }
            } else {
                return true;
            }
        } else {
            if (!is_null($this->logger)) {
                $this->logger->warning("Application validation errors found in main object: ",
                    array('errors' => $validator->errors()));
            }
            return $validator->errors();
        }
    }

    public function toJson()
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("Application::toJson() called");
        }
        $r = $this->validate();
        if ($r === true) {
            return json_encode($this->toArray());
        } else {
            return false;
        }
    }

    public function toArray()
    {
        if (!is_null($this->logger)) {
            $this->logger->debug("Application::toArray() called");
        }
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
        } else {
            return false;
        }
    }
}