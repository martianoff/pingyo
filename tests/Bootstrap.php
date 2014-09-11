<?php

error_reporting(E_ALL | E_STRICT);

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die("Dependencies must be installed using composer:\n\nphp composer.phar install --dev\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}

$root = realpath(dirname(dirname(__FILE__)));
$library = "$root/src";

$path = array($library, get_include_path());
set_include_path(implode(PATH_SEPARATOR, $path));

require_once 'PingYo/PingYoApplication.php';
require_once 'PingYo/PingYoSourceDetails.php';
require_once 'PingYo/PingYoApplicationDetails.php';
require_once 'PingYo/PingYoStatus.php';

require_once 'PingYo/PingYoVarTitles.php';
require_once 'PingYo/PingYoVarBankCardType.php';
require_once 'PingYo/PingYoVarResidentialStatus.php';
require_once 'PingYo/PingYoVarIncomePaymentType.php';
require_once 'PingYo/PingYoVarPayFrequency.php';
require_once 'PingYo/PingYoVarIncomeSource.php';
require_once 'PingYo/PingYoVarEmployerIndustry.php';
require_once 'PingYo/PingYoVarNationalIdentityNumberType.php';

// Include the composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

unset($root, $library, $path);