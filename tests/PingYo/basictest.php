<?php

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    //

    public function testRequired_withInvalid()
    {
        $v = new PingYo\ExtendedValidator(array('nothing' => "nothing", 'phone'=>'123'));
        $v->rule('required_with', ['country'], 'phone');
        $this->assertFalse($v->validate());
    }

    public function testRequired_withoutInvalid()
    {
        $v = new PingYo\ExtendedValidator(array('credit_card' => null));
        $v->rule('required_without', ['credit_card'], 'bank_account');
        $this->assertFalse($v->validate());
    }

    public function testPhoneInvalid()
    {
        $v = new PingYo\ExtendedValidator(array('phone' => '+12821379167','country' => 'GB'));
        $v->rule('phone', ['phone'], 'country');
        $this->assertFalse($v->validate());
    }

    public function testRequired_ifInvalid()
    {
        $v = new PingYo\ExtendedValidator(array('good_type' => 'auto'));
        $v->rule('required_if', ['type'], ['good_type',['fruit','auto']]);
        $this->assertFalse($v->validate());
    }

    public function testRequired_withValid()
    {
        $v = new PingYo\ExtendedValidator(array('phone' => '+12821379167','country' => 'US'));
        $v->rule('required_with', ['country'], 'phone');
        $this->assertTrue($v->validate());
    }

    public function testRequired_withoutValid()
    {
        $v = new PingYo\ExtendedValidator(array('credit_card' => '12321421','bank_account' => null));
        $v->rule('required_without', ['credit_card'], 'bank_account');
        $v->rule('required_without', ['bank_account'], 'credit_card');
        $this->assertTrue($v->validate());
    }

    public function testPhoneValid()
    {
        $v = new PingYo\ExtendedValidator(array('phone' => '+41446681800','country' => 'CH'));
        $v->rule('phone', ['phone'], 'country');
        $this->assertTrue($v->validate());
    }

    public function testRequired_ifValid()
    {
        $v = new PingYo\ExtendedValidator(array('type' => 'fruit','good_type' => 'auto'));
        $v->rule('required_if', ['type'], ['good_type',['fruit','cheap']]);
        $this->assertTrue($v->validate());
    }
    
    public function testConstantValidation()
    {
        $input = array('foo' => PingYo\TitleTypes::MR);
        $validator = new PingYo\ExtendedValidator($input);
        $validator->rule('required', 'foo');
        $validator->rule('in', 'foo', PingYo\TitleTypes::validation_set());
        $result = $validator->validate();
        $this->assertTrue($result);
    }


    // ...    
    public function testSourceDetails()
    {
        $a = new PingYo\SourceDetails();
        $this->assertInstanceOf('PingYo\SourceDetails', $a);
    }

    public function testSourceDetailsValidationFail()
    {
        $a = new PingYo\SourceDetails();
        $r = $a->validate();
        $this->assertNotTrue($r);
    }

    public function testSourceDetailsValidationPass()
    {
        $a = new PingYo\SourceDetails();
        $a->address = 'asd';
        $a->clientuseragent = 'asd';
        $a->creationurl = 'http://www.url.com';
        $r = $a->validate();
        $this->assertTrue($r);
    }


    public function testApplicationDetails()
    {
        $a = new PingYo\ApplicationDetails();
        $this->assertInstanceOf('PingYo\ApplicationDetails', $a);
    }

    public function testApplicationDetailsFail()
    {
        $a = new PingYo\ApplicationDetails();
        $r = $a->validate();
        $this->assertNotTrue($r);
    }

    public function testApplicationDetailsPass()
    {
        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "1994-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-01";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $r = $a->validate();
        $this->assertTrue($r);
    }


    public function testApplication()
    {
        $a = new PingYo\Application();
        $this->assertInstanceOf('PingYo\Application', $a);
    }

    public function testApplicationValidationFail()
    {
        $a = new PingYo\Application();
        $r = $a->validate();
        $this->assertNotTrue($r);
    }

    public function testApplicationValidationPass()
    {
        $a = new PingYo\Application();
        $a->affiliateid = 'abcd';
        $a->timeout = 120;
        $a->setApplicationDetails(new PingYo\ApplicationDetails());
        $a->setSourceDetails(new PingYo\SourceDetails());
        $r = $a->validate(false);
        $this->assertTrue($r);
    }


    public function testApplicationFullValidationFail()
    {
        $c = new PingYo\Application();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;

        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "2005-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-01";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);

        $r = $a->validate();
        $this->assertNotTrue($r);
    }


    public function testApplicationFullValidationPass()
    {
        $c = new PingYo\Application();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;

        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "1994-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-01";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);

        $r = $c->validate();
        $this->assertTrue($r);
    }


    public function testApplicationJsonPass()
    {
        $c = new PingYo\Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "1994-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-10";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);

        $t = $c->toJson();

        json_decode($t);

        $this->assertTrue((json_last_error() == JSON_ERROR_NONE) && (!empty($t)));
    }


    public function testApplicationSendInvalidAff()
    {
        $c = new PingYo\Application();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "1994-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-01";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);

        $t = $c->send();

        $this->assertTrue($t->httpcode == 403);
    }


    public function testApplicationSendPass()
    {
        $c = new PingYo\Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "1994-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-10";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);

        $t = $c->send();

        $this->assertTrue($t->httpcode == 202);
    }


    public function testApplicationSendPassWaitComplete()
    {
        $c = new PingYo\Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "1994-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-10";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);

        $t = $c->send();

        $final_status = "";
        if ($t->httpcode == 202) {
            while ($t->percentagecomplete != 100) {
                $t->refresh();
                //echo $t->percentagecomplete.'% ('.$t->status.')'."\n";
                sleep(2);
            }
            $final_status = $t->status;
        }

        $this->assertTrue($final_status == 'NoLenderMatchFound' || $final_status == 'LenderMatchFound');
    }


    public function testApplicationSendAsyncCode()
    {
        //....
        //async requests code
        $application_status = PingYo\Status::CreateFromCorrelationId('ef8285ed-3af0-45dd-a7bf-d84e8ac80a28');
        $application_status->refresh();
        $this->assertTrue($application_status->message == 'Unknown Correlation Id');
    }


    public function testApplicationSendPassWaitLoggerTest()
    {
        $c = new PingYo\Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        if (file_exists("pingyo-test1.log")) {
            unlink("pingyo-test1.log");
        }

        $logger = new \Monolog\Logger("PingYo");
        $logger->pushHandler(new \Monolog\Handler\StreamHandler("pingyo-test1.log"));
        $c->attachLogger($logger);

        $a = new PingYo\ApplicationDetails();

        $a->title = PingYo\TitleTypes::MR;
        $a->firstname = "John";
        $a->lastname = "Smith";
        $a->dateofbirth = "1994-09-01";
        $a->email = "johnsmith@domain.com";
        $a->homephonenumber = "+12345678900";
        $a->mobilephonenumber = "07123456789";
        $a->workphonenumber = "+12345678900";

        $a->employername = "Test Corp";
        $a->jobtitle = "Construction Worker";
        $a->employmentstarted = "2014-09-01";
        $a->employerindustry = PingYo\EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = PingYo\IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PingYo\PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = PingYo\IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = "2014-10-01";
        $a->followingpaydate = "2014-10-10";
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = PingYo\NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = PingYo\ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = PingYo\BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ["x" => "hello", "y" => "world"];

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);

        $t = $c->send();

        $final_status = "";
        if ($t->httpcode == 202) {
            while ($t->percentagecomplete != 100) {
                $t->refresh();
                //echo $t->percentagecomplete.'% ('.$t->status.')'."\n";
                sleep(2);
            }
            $final_status = $t->status;
        }

        $this->assertTrue(filesize("pingyo-test1.log") > 0);
    }


    public function testApplicationSendAsyncCodeLoggerTest()
    {
        //....
        //async requests code
        if (file_exists("pingyo-test2.log")) {
            unlink("pingyo-test2.log");
        }

        $logger = new \Monolog\Logger("PingYo");
        $logger->pushHandler(new \Monolog\Handler\StreamHandler("pingyo-test2.log"));

        $application_status = PingYo\Status::CreateFromCorrelationId('ef8285ed-3af0-45dd-a7bf-d84e8ac80a28', $logger);
        $application_status->refresh();

        $this->assertTrue(filesize("pingyo-test2.log") > 0);
    }
    // ...
}