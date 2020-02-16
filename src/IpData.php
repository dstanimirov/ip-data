<?php

namespace Openapi\Phalcon\Plugins;

use Phalcon\Config;
use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Exception AS PhalconException;

/**
 * 
 * Phalcon IP data plugin
 *   
 * @author Dimitar Stanimirov <stanimirov.dimitar@gmail.com>
 * 
 */
class IpData {

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $city;

    /**
     * @var bool
     */
    private $is_eu;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $country_name;

    /**
     * @var string
     */
    private $country_code;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var string
     */
    private $asn;

    /**
     * @var string
     */
    private $organisation;

    /**
     * @var string
     */
    private $postal;

    /**
     * @var \Phalcon\Config
     */
    private $currency;

    /**
     * @var string
     */
    private $currency_code;

    /**
     * @var \Phalcon\Config
     */
    private $time_zone;

    /**
     * @var string
     */
    private $current_time;

    /**
     * @var \Phalcon\Config
     */
    private $languages;

    /**
     * @var array Error messages
     */
    private $errors = [];

    public function __construct() {
        
    }

    /**
     * @return string
     */
    public function getIpAddress() {
        return $this->ip;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey) {

        $this->apiKey = $apiKey;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl) {

        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getAsn() {
        return $this->asn;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @return bool Country in EU
     */
    public function isEu() {
        return !empty($this->is_eu) ? true : false;
    }

    /**
     * @return string
     */
    public function getCountry() {

        return $this->country_name;
    }

    /**
     * @return string
     */
    public function getCountryCode() {

        return $this->country_code;
    }

    /**
     * @return string
     */
    public function getRegion() {

        return $this->region;
    }

    /**
     * @return string
     */
    public function getPostalCode() {

        return $this->postal;
    }

    /**
     * @return string
     */
    public function getOrganization() {

        return $this->organisation;
    }

    /**
     * @return float
     */
    public function getLatitude() {

        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude() {

        return $this->longitude;
    }

    /**
     * @return \Phalcon\Config keys[name, code, symbol, native, plural]
     */
    public function getCurrency() {

        return !empty($this->currency) ? $this->currency : new Config([]);
    }

    /**
     * @return string currency code
     * @return bool FALSE
     */
    public function getCurrencyCode() {

        return $this->currency_code = $this->currency instanceof Config ? $this->currency->path('code', false) : false;
    }

    /**
     * @return \Phalcon\Config keys[name, abbr, offset, is_dst, current_time]
     */
    public function getTimeZone() {

        return $this->current_time = !empty($this->time_zone) ? $this->time_zone : new Config([]);
    }

    /**
     * @return string datetime
     * @return bool FALSE
     */
    public function getCurrentTime() {

        if (!empty($this->current_time)) {

            return $this->current_time;
        }

        return $this->current_time = $this->time_zone instanceof Config ? $this->time_zone->path('current_time', false) : false;
    }

    /**
     * @return \Phalcon\Config
     */
    public function getLanguages() {

        return $this->languages = !empty($this->languages) ? $this->languages : new Config([]);
    }

    /**
     * @param string $ipAddress IP address to lookup. If is not set, client browser IP address will be used
     * 
     * @return \Phalcon\Config 
     */
    public function locate($ipAddress = '') {

        if (empty($this->apiKey) || empty($this->baseUrl)) {

            return new Config([]);
        }

        $method = new Curl();

        $params = [
            'api-key' => $this->apiKey
        ];

        $this->baseUrl .= !empty($ipAddress) ? '/' . $ipAddress : '';

        try {

            /* @var $response \Phalcon\Http\Client\Response */
            $response = $method->get($this->baseUrl, $params, ['Accept: application/json']);
        } catch (PhalconException $exc) {

            $this->setError($exc->getMessage());

            return new Config([]);
        }

        $decode = json_decode($response->body, true);

        if (!is_array($decode)) {

            return new Config([]);
        }

        $data = new Config($decode);

        if (!empty($data->path('message'))) {

            $this->setError($data->path('message'));
        }

        //sets to properties if exists
        foreach ($data as $property => $value) {

            if (property_exists(get_class(), $property)) {

                $this->{$property} = $value;
            }
        }

        return $data;
    }

    /**
     * @return array Error messages
     */
    public function getErrors() {

        return !empty($this->errors) ? $this->errors : false;
    }

    /**
     * @return string Get the first error message
     */
    public function getFirstError() {

        return !empty($this->errors[0]) ? $this->errors[0] : false;
    }

    /**
     * Add message to list with error messages
     * 
     * @param string $message
     */
    private function setError($message = false) {

        if (empty($message)) {
            return false;
        }

        $this->errors[] = $message;
    }

}
