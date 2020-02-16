<?php

namespace Openapi\Phalcon\Plugins;

use Phalcon\Config;
use Phalcon\Http\Client\Provider\Curl;
use Phalcon\Http\Client\Exception AS PhalconException;

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
     * @param string $ipAddress IP address to lookup. If is not set, client browser IP address will be used
     * 
     * @return \Phalcon\Config 
     */
    public function locate($ipAddress = '') {

        if (empty($this->apiKey) || empty($this->baseUrl)) {
            return false;
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

            return false;
        }

        $decode = json_decode($response->body, true);

        if (!is_array($decode)) {

            return false;
        }

        $data = new Config($decode);

        if (!empty($data->path('message'))) {

            $this->setError($data->path('message'));

            return false;
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