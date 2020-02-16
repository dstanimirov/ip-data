<?php

/* 
 * Example how to use Phalcon IP data plugin
 * 
 * https://ipdata.co/  to get your API key
 * 
 * https://docs.ipdata.co/ documentation
 * 
 */


require_once('vendor/autoload.php');

use Openapi\Phalcon\Plugins\IpData;

$ipdata = new IpData();

$ipdata->setApiKey('your-api-key');

$ipdata->setBaseUrl('https://api.ipdata.co');

$data = $ipdata->locate();

//check for errors
$ipdata->getErrors();

//get first error
$ipdata->getFirstError();

//get value directly from API response 
$data->path('city');

//get value from class method
$ipdata->getCity();

$ipdata->getCurrencyCode();

$ipdata->getCurrentTime();

$ipdata->getLanguages();
