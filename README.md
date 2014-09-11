## Installation

PingYoApplication uses [Composer](http://getcomposer.org) to install and update:

```
curl -s http://getcomposer.org/installer | php
php composer.phar require maksimru/pingyoapplication
```

## Usage

Enum fields will be converted to integer automatically.
Dates will be converted to JS Date objects automatically.

Step 1. Create Source Details object
```php
$source_details = new PingYoSourceDetails();
$source_details->address = 'asd';
$source_details->clientuseragent = 'asd';
$source_details->creationurl = 'http://www.url.com';
```

Step 2. Create Application Details object

```php
$application_details = new PingYoApplicationDetails();

$application_details->title = 'Mr';
$application_details->firstname = "John";
$application_details->lastname = "Smith";
$application_details->dateofbirth = "1994-09-01";
$application_details->email = "johnsmith@domain.com";
$application_details->homephonenumber = "+12345678900";
$application_details->mobilephonenumber = "07123456789";
$application_details->workphonenumber = "+12345678900";

$application_details->employername = "Test Corp";
$application_details->jobtitle = "Construction Worker";
$application_details->employmentstarted = "2014-09-01";
$application_details->employerindustry = "ConstructionManufacturing";
$application_details->incomesource = "EmployedFullTime";
$application_details->payfrequency = "LastWorkingDayMonth";
$application_details->payamount = 100;
$application_details->incomepaymenttype = "RegionalDirectDeposit";
$application_details->nextpaydate = "2014-10-01";
$application_details->followingpaydate = "2014-10-10";
$application_details->loanamount = 10000;
$application_details->nationalidentitynumber = null;
$application_details->nationalidentitynumbertype = 'NationalInsurance';
$application_details->consenttocreditsearch = true;
$application_details->consenttomarketingemails = true;
$application_details->residentialstatus = "HomeOwner";

$application_details->housenumber = "122";
$application_details->housename = null;
$application_details->addressstreet1 = "Test Street";
$application_details->addresscity = "Test City";
$application_details->addresscountrycode = "GB";
$application_details->addresscounty = "County Test";
$application_details->addressmovein = "2014-08-01";
$application_details->addresspostcode = "BT602EW";

$application_details->bankaccountnumber = "12345678";
$application_details->bankcardtype = "VisaDebit";
$application_details->bankroutingnumber = "123456";
$application_details->monthlymortgagerent = 600;
$application_details->monthlycreditcommitments = 100;
$application_details->otherexpenses = 250;
$application_details->minimumcommissionamount = 0;
$application_details->maximumcommissionamount = 0;
$application_details->applicationextensions = ["x"=>"hello","y"=>"world"];
```

Step 3. Create Application object and attach PingYoApplicationDetails and PingYoSourceDetails objects

```php
$application = new PingYoApplication();
$application->affiliateid = 'TEST';
$application->timeout = 120;
$application->testonly = true;
$application->applicationdetails = $application_details;
$application->sourcedetails = $source_details;
```

Step 4. Validate

```php
$validation_result = $application->validate();
if($validation_result)
{
	//prevalidation OK
	//next step
}
else
{
	var_dump($validation_result);
	//$validation_result contains validation problems
}
```

Step 5. Send Application

```php
$application_status = $application->send();
if($application_status===false)
{
	//validation problems
	//step 4 missed?
}
else
{
	//next step
}
```

Step 6. Check Result Application

```php
if($application_status->httpcode=202)
{
	//all fine, server validation passed
	//next step
}
else
{
	//check problems
	var_dump($application_status->errors);
	var_dump($application_status->message);
}

```
Step 7. Take Redirect Url. You can wait for result in loop or use async requests

```php
while ($application_status->percentagecomplete!=100)
{	
	$application_status->refresh();
	sleep(2);
}
$final_status = $application_status->status;
switch($final_status){
	case 'LenderMatchFound':
		$redirect_location = $application_status->redirectionurl;
	break;
	case 'Unknown':
		//some action
	break;
	case 'Resurrecting':
		//some action
	break;
	case 'Processing':
		//some action
	break;
	case 'LenderMatchFound':
		//some action
	break;
	case 'ConditionalLenderMatchFound':
		//some action
	break;
	case 'NoLenderMatchFound':
		//some action
	break;
	case 'Rejected':
		//some action
	break;
	case 'Withdrawn':
		//some action
	break;
	case 'Erred':
		//some action
	break;
}
```