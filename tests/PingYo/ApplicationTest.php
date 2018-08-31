<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use PingYo\Application;
use PingYo\ApplicationDetails;
use PingYo\BankCardTypes;
use PingYo\EmployerIndustryTypes;
use PingYo\IncomePaymentTypes;
use PingYo\IncomeSourceTypes;
use PingYo\LoanProceedUses;
use PingYo\MaritalStatuses;
use PingYo\NationalIdentityNumberTypes;
use PingYo\PayFrequencyTypes;
use PingYo\ResidentialStatusTypes;
use PingYo\SourceDetails;
use PingYo\Status;
use PingYo\TitleTypes;

class ApplicationTest extends TestCase
{
    public function testRequired_withInvalid()
    {
        $v = new PingYo\ExtendedValidator(array('nothing' => "nothing", 'phone' => '123'));
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
        $v = new PingYo\ExtendedValidator(array('phone' => '+12821379167', 'country' => 'GB'));
        $v->rule('phone', ['phone'], 'country');
        $this->assertFalse($v->validate());
    }

    public function testRequired_ifInvalid()
    {
        $v = new PingYo\ExtendedValidator(array('good_type' => 'auto'));
        $v->rule('required_if', ['type'], ['good_type', ['fruit', 'auto']]);
        $this->assertFalse($v->validate());
    }

    public function testRequired_withValid()
    {
        $v = new PingYo\ExtendedValidator(array('phone' => '+12821379167', 'country' => 'US'));
        $v->rule('required_with', ['country'], 'phone');
        $this->assertTrue($v->validate());
    }

    public function testRequired_withoutValid()
    {
        $v = new PingYo\ExtendedValidator(array('credit_card' => '12321421', 'bank_account' => null));
        $v->rule('required_without', ['credit_card'], 'bank_account');
        $v->rule('required_without', ['bank_account'], 'credit_card');
        $this->assertTrue($v->validate());
    }

    public function testPhoneValid()
    {
        $v = new PingYo\ExtendedValidator(array('phone' => '+41446681800', 'country' => 'CH'));
        $v->rule('phone', ['phone'], 'country');
        $this->assertTrue($v->validate());
    }

    public function testRequired_ifValid()
    {
        $v = new PingYo\ExtendedValidator(array('type' => 'fruit', 'good_type' => 'auto'));
        $v->rule('required_if', ['type'], ['good_type', ['fruit', 'cheap']]);
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
        $a->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
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
        $a->firstname = 'John';
        $a->lastname = 'Smith';
        $a->dateofbirth = '1994-09-01';
        $a->email = 'johnsmith@domain.com';
        $a->homephonenumber = '+12345678900';
        $a->mobilephonenumber = '07123456789';
        $a->workphonenumber = '+12345678900';

        $a->employername = 'Test Corp';
        $a->jobtitle = 'Construction Worker';
        $a->employmentstarted = '2014-09-01';
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime('+5 days'))->format('Y-m-d');
        $a->followingpaydate = (new DateTime('+35 days'))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = '122';
        $a->housename = null;
        $a->addressstreet1 = 'Test Street';
        $a->addresscity = 'Test City';
        $a->addresscountrycode = 'GB';
        $a->addresscounty = 'County Test';
        $a->addressmovein = '2014-08-01';
        $a->addresspostcode = 'BT602EW';

        $a->bankaccountnumber = '12345678';
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = '123456';
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = ['x' => 'hello', 'y' => 'world'];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

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
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime('+5 days'))->format('Y-m-d');
        $a->followingpaydate = (new DateTime('+35 days'))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = [["x" => "hello"], ["y" => "world"]];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

        $c->setApplicationDetails($a);

        $b = new SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
        $b->creationurl = 'http://www.url.com';
        $b->languagecodes = [
            'en-GB'
        ];

        $c->setSourceDetails($b);

        $r = $a->validate();
        $this->assertNotTrue($r);
    }


    public function testApplicationFullValidationPass()
    {
        $c = new Application();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;

        $a = new ApplicationDetails();

        $a->title = TitleTypes::MR;
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
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime('+5 days'))->format('Y-m-d');
        $a->followingpaydate = (new DateTime('+35 days'))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = [["x" => "hello"], ["y" => "world"]];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

        $c->setApplicationDetails($a);

        $b = new SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
        $b->creationurl = 'http://www.url.com';
        $b->languagecodes = [
            'en-GB'
        ];

        $c->setSourceDetails($b);

        $r = $c->validate();
        $this->assertTrue($r);
    }


    public function testApplicationJsonPass()
    {
        $c = new Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new ApplicationDetails();

        $a->title = TitleTypes::MR;
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
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime('+5 days'))->format('Y-m-d');
        $a->followingpaydate = (new DateTime('+35 days'))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = [["x" => "hello"], ["y" => "world"]];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

        $c->setApplicationDetails($a);

        $b = new SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
        $b->creationurl = 'http://www.url.com';
        $b->languagecodes = [
            'en-GB'
        ];

        $c->setSourceDetails($b);

        $t = $c->toJson();

        json_decode($t);

        $this->assertTrue((json_last_error() == JSON_ERROR_NONE) && (!empty($t)));
    }


    public function testApplicationSendInvalidAff()
    {
        $c = new Application();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new ApplicationDetails();

        $a->title = TitleTypes::MR;
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
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime('+5 days'))->format('Y-m-d');
        $a->followingpaydate = (new DateTime('+35 days'))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = [["x" => "hello"], ["y" => "world"]];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

        $c->setApplicationDetails($a);

        $b = new SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
        $b->creationurl = 'http://www.url.com';
        $b->languagecodes = [
            'en-GB'
        ];

        $c->setSourceDetails($b);

        $t = $c->send();

        $this->assertEquals($t->httpcode, 403);
    }


    public function testApplicationSendPass()
    {
        $c = new Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new ApplicationDetails();

        $a->title = TitleTypes::MR;
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
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime("+5 days"))->format('Y-m-d');
        $a->followingpaydate = (new DateTime("+35 days"))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = [["x" => "hello"], ["y" => "world"]];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

        $c->setApplicationDetails($a);

        $b = new SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
        $b->creationurl = 'http://www.url.com';
        $b->languagecodes = [
            'en-GB'
        ];

        $c->setSourceDetails($b);

        $t = $c->send();

        $this->assertEquals($t->httpcode, 202);
    }


    public function testApplicationSendPassWaitComplete()
    {
        $c = new Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        $a = new ApplicationDetails();

        $a->title = TitleTypes::MR;
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
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime("+5 days"))->format('Y-m-d');
        $a->followingpaydate = (new DateTime("+35 days"))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = [["x" => "hello"], ["y" => "world"]];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

        $c->setApplicationDetails($a);

        $b = new SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
        $b->creationurl = 'http://www.url.com';
        $b->languagecodes = [
            'en-GB'
        ];

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

        $this->assertTrue($final_status === 'NoLenderMatchFound' || $final_status === 'LenderMatchFound');
    }

    public function testApplicationSendAsyncCode()
    {
        //async requests code
        $application_status = Status::CreateFromCorrelationId('ef8285ed-3af0-45dd-a7bf-d84e8ac80a28');
        $application_status->refresh();
        $this->assertEquals($application_status->message, 'Unknown Correlation Id');
    }

    public function testApplicationSendPassWaitLoggerTest()
    {
        $c = new Application();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;

        if (file_exists("pingyo-test1.log")) {
            unlink("pingyo-test1.log");
        }

        $logger = new \Monolog\Logger("PingYo");
        $logger->pushHandler(new StreamHandler('pingyo-test1.log'));
        $c->attachLogger($logger);

        $a = new ApplicationDetails();

        $a->title = TitleTypes::MR;
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
        $a->employerindustry = EmployerIndustryTypes::ConstructionManufacturing;
        $a->incomesource = IncomeSourceTypes::EmployedFullTime;
        $a->payfrequency = PayFrequencyTypes::LastWorkingDayMonth;
        $a->payamount = 100;
        $a->incomepaymenttype = IncomePaymentTypes::RegionalDirectDeposit;
        $a->nextpaydate = (new DateTime("+5 days"))->format('Y-m-d');
        $a->followingpaydate = (new DateTime("+35 days"))->format('Y-m-d');
        $a->loanamount = 10000;
        $a->nationalidentitynumber = null;
        $a->nationalidentitynumbertype = NationalIdentityNumberTypes::NationalInsurance;
        $a->consenttocreditsearch = true;
        $a->consenttomarketingemails = true;
        $a->residentialstatus = ResidentialStatusTypes::HomeOwner;

        $a->housenumber = "122";
        $a->housename = null;
        $a->addressstreet1 = "Test Street";
        $a->addresscity = "Test City";
        $a->addresscountrycode = "GB";
        $a->addresscounty = "County Test";
        $a->addressmovein = "2014-08-01";
        $a->addresspostcode = "BT602EW";

        $a->bankaccountnumber = "12345678";
        $a->bankcardtype = BankCardTypes::VisaDebit;
        $a->bankroutingnumber = "123456";
        $a->monthlymortgagerent = 600;
        $a->monthlycreditcommitments = 100;
        $a->otherexpenses = 250;
        $a->minimumcommissionamount = 0;
        $a->maximumcommissionamount = 0;
        $a->applicationextensions = [["x" => "hello"], ["y" => "world"]];

        $a->combinedMonthlyHouseholdIncome = 100;
        $a->confirmedByApplicant = true;
        $a->consentToMarketingPhone = true;
        $a->consentToMarketingSms = true;
        $a->food = 100;
        $a->loanProceedUse = LoanProceedUses::Other;
        $a->maritalStatus = MaritalStatuses::Other;
        $a->numberOfDependents = 0;
        $a->term = 2;
        $a->transport = 200;
        $a->usesOnlineBanking = true;
        $a->utilities = 300;

        $c->setApplicationDetails($a);

        $b = new PingYo\SourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0;)';
        $b->creationurl = 'http://www.url.com';
        $b->languagecodes = [
            'en-GB'
        ];

        $c->setSourceDetails($b);

        $t = $c->send();

        $final_status = "";
        if ($t->httpcode === 202) {
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

        $logger = new Logger("PingYo");
        $logger->pushHandler(new StreamHandler("pingyo-test2.log"));

        $application_status = PingYo\Status::CreateFromCorrelationId('ef8285ed-3af0-45dd-a7bf-d84e8ac80a28', $logger);
        $application_status->refresh();

        $this->assertTrue(filesize("pingyo-test2.log") > 0);
    }
    // ...
}