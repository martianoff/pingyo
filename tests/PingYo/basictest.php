<?php

class PingYoApplicationTest extends PHPUnit_Framework_TestCase
{
	// 
	
	public function testConstantValidation()
    {
    	$input = array('foo'=>PingYoVarTitles::MR);
    	$validator = new Valitron\Validator($input);
    	$validator->rule('required','foo');
    	$validator->rule('in','foo',PingYoVarTitles::validation_set());
        $result = $validator->validate();
        $this->assertTrue($result);
    }
    
    
    
    // ...    
    public function testPingYoSourceDetails()
    {
        $a = new PingYoSourceDetails();   
        $this->assertInstanceOf('PingYoSourceDetails', $a);
    }
    
    public function testPingYoSourceDetailsValidationFail()
    {
        $a = new PingYoSourceDetails();  
		$r = $a->validate();
        $this->assertNotTrue($r);
    }
    
    public function testPingYoSourceDetailsValidationPass()
    {
        $a = new PingYoSourceDetails();
        $a->address = 'asd';
        $a->clientuseragent = 'asd';
        $a->creationurl = 'http://www.url.com';
		$r = $a->validate();
        $this->assertTrue($r);
    }
    
    
       
    
    public function testPingYoApplicationDetails()
    {
        $a = new PingYoApplicationDetails();   
        $this->assertInstanceOf('PingYoApplicationDetails', $a);
    }
    
    public function testPingYoApplicationDetailsFail()
    {
        $a = new PingYoApplicationDetails();  
		$r = $a->validate();
        $this->assertNotTrue($r);
    }
    
    public function testPingYoApplicationDetailsPass()
    {
        $a = new PingYoApplicationDetails();

        $a->title = PingYoVarTitles::MR;
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
		$a->employerindustry = PingYoVarEmployerIndustry::ConstructionManufacturing;
		$a->incomesource = PingYoVarIncomeSource::EmployedFullTime;
		$a->payfrequency = PingYoVarPayFrequency::LastWorkingDayMonth;
		$a->payamount = 100;
		$a->incomepaymenttype = PingYoVarIncomePaymentType::RegionalDirectDeposit;
		$a->nextpaydate = "2014-10-01";
		$a->followingpaydate = "2014-10-01";
		$a->loanamount = 10000;
		$a->nationalidentitynumber = null;
		$a->nationalidentitynumbertype = PingYoVarNationalIdentityNumberType::NationalInsurance;
		$a->consenttocreditsearch = true;
		$a->consenttomarketingemails = true;
		$a->residentialstatus = PingYoVarResidentialStatus::HomeOwner;
		
		$a->housenumber = "122";
		$a->housename = null;
		$a->addressstreet1 = "Test Street";
		$a->addresscity = "Test City";
		$a->addresscountrycode = "GB";
		$a->addresscounty = "County Test";
		$a->addressmovein = "2014-08-01";
		$a->addresspostcode = "BT602EW";
		
		$a->bankaccountnumber = "12345678";
		$a->bankcardtype = PingYoVarBankCardType::VisaDebit;
		$a->bankroutingnumber = "123456";
		$a->monthlymortgagerent = 600;
		$a->monthlycreditcommitments = 100;
		$a->otherexpenses = 250;
		$a->minimumcommissionamount = 0;
		$a->maximumcommissionamount = 0;
		$a->applicationextensions = ["x"=>"hello","y"=>"world"];

		$r = $a->validate();
        $this->assertTrue($r);
    }
    
    
    
    
    public function testPingYoApplication()
    {
        $a = new PingYoApplication();   
        $this->assertInstanceOf('PingYoApplication', $a);
    }
    
    public function testPingYoApplicationValidationFail()
    {
        $a = new PingYoApplication();  
		$r = $a->validate();
        $this->assertNotTrue($r);
    }
    
    public function testPingYoApplicationValidationPass()
    {
        $a = new PingYoApplication();
        $a->affiliateid = 'abcd';
        $a->timeout = 120;
        $a->setApplicationDetails(new PingYoApplicationDetails());
        $a->setSourceDetails(new PingYoSourceDetails());
		$r = $a->validate(false);
        $this->assertTrue($r);
    }
    


	public function testPingYoApplicationFullValidationFail(){
	    $c = new PingYoApplication();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;
        
        $a = new PingYoApplicationDetails();

        $a->title = PingYoVarTitles::MR;
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
		$a->employerindustry = PingYoVarEmployerIndustry::ConstructionManufacturing;
		$a->incomesource = PingYoVarIncomeSource::EmployedFullTime;
		$a->payfrequency = PingYoVarPayFrequency::LastWorkingDayMonth;
		$a->payamount = 100;
		$a->incomepaymenttype = PingYoVarIncomePaymentType::RegionalDirectDeposit;
		$a->nextpaydate = "2014-10-01";
		$a->followingpaydate = "2014-10-01";
		$a->loanamount = 10000;
		$a->nationalidentitynumber = null;
		$a->nationalidentitynumbertype = PingYoVarNationalIdentityNumberType::NationalInsurance;
		$a->consenttocreditsearch = true;
		$a->consenttomarketingemails = true;
		$a->residentialstatus = PingYoVarResidentialStatus::HomeOwner;
		
		$a->housenumber = "122";
		$a->housename = null;
		$a->addressstreet1 = "Test Street";
		$a->addresscity = "Test City";
		$a->addresscountrycode = "GB";
		$a->addresscounty = "County Test";
		$a->addressmovein = "2014-08-01";
		$a->addresspostcode = "BT602EW";
		
		$a->bankaccountnumber = "12345678";
		$a->bankcardtype = PingYoVarBankCardType::VisaDebit;
		$a->bankroutingnumber = "123456";
		$a->monthlymortgagerent = 600;
		$a->monthlycreditcommitments = 100;
		$a->otherexpenses = 250;
		$a->minimumcommissionamount = 0;
		$a->maximumcommissionamount = 0;
		$a->applicationextensions = ["x"=>"hello","y"=>"world"];
		
		$c->setApplicationDetails($a);
		
		$b = new PingYoSourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);
		
		$r = $a->validate();
        $this->assertNotTrue($r);
    }


    public function testPingYoApplicationFullValidationPass(){
	    $c = new PingYoApplication();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;
        
        $a = new PingYoApplicationDetails();

        $a->title = PingYoVarTitles::MR;
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
		$a->employerindustry = PingYoVarEmployerIndustry::ConstructionManufacturing;
		$a->incomesource = PingYoVarIncomeSource::EmployedFullTime;
		$a->payfrequency = PingYoVarPayFrequency::LastWorkingDayMonth;
		$a->payamount = 100;
		$a->incomepaymenttype = PingYoVarIncomePaymentType::RegionalDirectDeposit;
		$a->nextpaydate = "2014-10-01";
		$a->followingpaydate = "2014-10-01";
		$a->loanamount = 10000;
		$a->nationalidentitynumber = null;
		$a->nationalidentitynumbertype = PingYoVarNationalIdentityNumberType::NationalInsurance;
		$a->consenttocreditsearch = true;
		$a->consenttomarketingemails = true;
		$a->residentialstatus = PingYoVarResidentialStatus::HomeOwner;
		
		$a->housenumber = "122";
		$a->housename = null;
		$a->addressstreet1 = "Test Street";
		$a->addresscity = "Test City";
		$a->addresscountrycode = "GB";
		$a->addresscounty = "County Test";
		$a->addressmovein = "2014-08-01";
		$a->addresspostcode = "BT602EW";
		
		$a->bankaccountnumber = "12345678";
		$a->bankcardtype = PingYoVarBankCardType::VisaDebit;
		$a->bankroutingnumber = "123456";
		$a->monthlymortgagerent = 600;
		$a->monthlycreditcommitments = 100;
		$a->otherexpenses = 250;
		$a->minimumcommissionamount = 0;
		$a->maximumcommissionamount = 0;
		$a->applicationextensions = ["x"=>"hello","y"=>"world"];
		
		$c->setApplicationDetails($a);
		
		$b = new PingYoSourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);
		
		$r = $c->validate();
        $this->assertTrue($r);
    }
    
    
    public function testPingYoApplicationJsonPass(){
	    $c = new PingYoApplication();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;
        
        $a = new PingYoApplicationDetails();

        $a->title = PingYoVarTitles::MR;
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
		$a->employerindustry = PingYoVarEmployerIndustry::ConstructionManufacturing;
		$a->incomesource = PingYoVarIncomeSource::EmployedFullTime;
		$a->payfrequency = PingYoVarPayFrequency::LastWorkingDayMonth;
		$a->payamount = 100;
		$a->incomepaymenttype = PingYoVarIncomePaymentType::RegionalDirectDeposit;
		$a->nextpaydate = "2014-10-01";
		$a->followingpaydate = "2014-10-10";
		$a->loanamount = 10000;
		$a->nationalidentitynumber = null;
		$a->nationalidentitynumbertype = PingYoVarNationalIdentityNumberType::NationalInsurance;
		$a->consenttocreditsearch = true;
		$a->consenttomarketingemails = true;
		$a->residentialstatus = PingYoVarResidentialStatus::HomeOwner;
		
		$a->housenumber = "122";
		$a->housename = null;
		$a->addressstreet1 = "Test Street";
		$a->addresscity = "Test City";
		$a->addresscountrycode = "GB";
		$a->addresscounty = "County Test";
		$a->addressmovein = "2014-08-01";
		$a->addresspostcode = "BT602EW";
		
		$a->bankaccountnumber = "12345678";
		$a->bankcardtype = PingYoVarBankCardType::VisaDebit;
		$a->bankroutingnumber = "123456";
		$a->monthlymortgagerent = 600;
		$a->monthlycreditcommitments = 100;
		$a->otherexpenses = 250;
		$a->minimumcommissionamount = 0;
		$a->maximumcommissionamount = 0;
		$a->applicationextensions = ["x"=>"hello","y"=>"world"];
		
		$c->setApplicationDetails($a);
		
		$b = new PingYoSourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);
		
		$t = $c->toJson();
						
		json_decode($t);
		
        $this->assertTrue((json_last_error() == JSON_ERROR_NONE)&&(!empty($t)));
    }
    
    
    
    public function testPingYoApplicationSendInvalidAff(){
	    $c = new PingYoApplication();
        $c->affiliateid = 'abcd';
        $c->timeout = 120;
        $c->testonly = true;
        
        $a = new PingYoApplicationDetails();

        $a->title = PingYoVarTitles::MR;
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
		$a->employerindustry = PingYoVarEmployerIndustry::ConstructionManufacturing;
		$a->incomesource = PingYoVarIncomeSource::EmployedFullTime;
		$a->payfrequency = PingYoVarPayFrequency::LastWorkingDayMonth;
		$a->payamount = 100;
		$a->incomepaymenttype = PingYoVarIncomePaymentType::RegionalDirectDeposit;
		$a->nextpaydate = "2014-10-01";
		$a->followingpaydate = "2014-10-01";
		$a->loanamount = 10000;
		$a->nationalidentitynumber = null;
		$a->nationalidentitynumbertype = PingYoVarNationalIdentityNumberType::NationalInsurance;
		$a->consenttocreditsearch = true;
		$a->consenttomarketingemails = true;
		$a->residentialstatus = PingYoVarResidentialStatus::HomeOwner;
		
		$a->housenumber = "122";
		$a->housename = null;
		$a->addressstreet1 = "Test Street";
		$a->addresscity = "Test City";
		$a->addresscountrycode = "GB";
		$a->addresscounty = "County Test";
		$a->addressmovein = "2014-08-01";
		$a->addresspostcode = "BT602EW";
		
		$a->bankaccountnumber = "12345678";
		$a->bankcardtype = PingYoVarBankCardType::VisaDebit;
		$a->bankroutingnumber = "123456";
		$a->monthlymortgagerent = 600;
		$a->monthlycreditcommitments = 100;
		$a->otherexpenses = 250;
		$a->minimumcommissionamount = 0;
		$a->maximumcommissionamount = 0;
		$a->applicationextensions = ["x"=>"hello","y"=>"world"];
		
		$c->setApplicationDetails($a);
		
		$b = new PingYoSourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);
		
		$t = $c->send();
								
        $this->assertTrue($t->httpcode==403);
    }
    
    
    public function testPingYoApplicationSendPass(){
	    $c = new PingYoApplication();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;
        
        $a = new PingYoApplicationDetails();

        $a->title = PingYoVarTitles::MR;
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
		$a->employerindustry = PingYoVarEmployerIndustry::ConstructionManufacturing;
		$a->incomesource = PingYoVarIncomeSource::EmployedFullTime;
		$a->payfrequency = PingYoVarPayFrequency::LastWorkingDayMonth;
		$a->payamount = 100;
		$a->incomepaymenttype = PingYoVarIncomePaymentType::RegionalDirectDeposit;
		$a->nextpaydate = "2014-10-01";
		$a->followingpaydate = "2014-10-10";
		$a->loanamount = 10000;
		$a->nationalidentitynumber = null;
		$a->nationalidentitynumbertype = PingYoVarNationalIdentityNumberType::NationalInsurance;
		$a->consenttocreditsearch = true;
		$a->consenttomarketingemails = true;
		$a->residentialstatus = PingYoVarResidentialStatus::HomeOwner;
		
		$a->housenumber = "122";
		$a->housename = null;
		$a->addressstreet1 = "Test Street";
		$a->addresscity = "Test City";
		$a->addresscountrycode = "GB";
		$a->addresscounty = "County Test";
		$a->addressmovein = "2014-08-01";
		$a->addresspostcode = "BT602EW";
		
		$a->bankaccountnumber = "12345678";
		$a->bankcardtype = PingYoVarBankCardType::VisaDebit;
		$a->bankroutingnumber = "123456";
		$a->monthlymortgagerent = 600;
		$a->monthlycreditcommitments = 100;
		$a->otherexpenses = 250;
		$a->minimumcommissionamount = 0;
		$a->maximumcommissionamount = 0;
		$a->applicationextensions = ["x"=>"hello","y"=>"world"];
		
		$c->setApplicationDetails($a);
		
		$b = new PingYoSourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);
		
		$t = $c->send();
								
        $this->assertTrue($t->httpcode==202);
    }
    
    
    
    public function testPingYoApplicationSendPassWaitComplete(){
	    $c = new PingYoApplication();
        $c->affiliateid = 'TEST';
        $c->timeout = 120;
        $c->testonly = true;
        
        $a = new PingYoApplicationDetails();

        $a->title = PingYoVarTitles::MR;
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
		$a->employerindustry = PingYoVarEmployerIndustry::ConstructionManufacturing;
		$a->incomesource = PingYoVarIncomeSource::EmployedFullTime;
		$a->payfrequency = PingYoVarPayFrequency::LastWorkingDayMonth;
		$a->payamount = 100;
		$a->incomepaymenttype = PingYoVarIncomePaymentType::RegionalDirectDeposit;
		$a->nextpaydate = "2014-10-01";
		$a->followingpaydate = "2014-10-10";
		$a->loanamount = 10000;
		$a->nationalidentitynumber = null;
		$a->nationalidentitynumbertype = PingYoVarNationalIdentityNumberType::NationalInsurance;
		$a->consenttocreditsearch = true;
		$a->consenttomarketingemails = true;
		$a->residentialstatus = PingYoVarResidentialStatus::HomeOwner;
		
		$a->housenumber = "122";
		$a->housename = null;
		$a->addressstreet1 = "Test Street";
		$a->addresscity = "Test City";
		$a->addresscountrycode = "GB";
		$a->addresscounty = "County Test";
		$a->addressmovein = "2014-08-01";
		$a->addresspostcode = "BT602EW";
		
		$a->bankaccountnumber = "12345678";
		$a->bankcardtype = PingYoVarBankCardType::VisaDebit;
		$a->bankroutingnumber = "123456";
		$a->monthlymortgagerent = 600;
		$a->monthlycreditcommitments = 100;
		$a->otherexpenses = 250;
		$a->minimumcommissionamount = 0;
		$a->maximumcommissionamount = 0;
		$a->applicationextensions = ["x"=>"hello","y"=>"world"];
		
		$c->setApplicationDetails($a);
		
		$b = new PingYoSourceDetails();
        $b->address = 'asd';
        $b->clientuseragent = 'asd';
        $b->creationurl = 'http://www.url.com';

        $c->setSourceDetails($b);
		
		$t = $c->send();
		
		$final_status = "";
		if($t->httpcode==202)
		{		
			while ($t->percentagecomplete!=100)
			{	
				$t->refresh();
				//echo $t->percentagecomplete.'% ('.$t->status.')'."\n";
				sleep(2);
			}
			$final_status = $t->status;
		}
								
        $this->assertTrue($final_status=='NoLenderMatchFound');
    }


	public function testPingYoApplicationSendAsyncCode(){
		//....
		//async requests code
		$application_status = PingYoStatus::CreateFromCorrelationId('ef8285ed-3af0-45dd-a7bf-d84e8ac80a28');
		$application_status->refresh();
		$this->assertTrue($application_status->message=='Unknown Correlation Id');
	}
    // ...
}