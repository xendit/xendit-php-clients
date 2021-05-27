<?php

/**
 * GuzzleClient.php
 * php version 7.2.0
 *
 * @category Class
 * @package  Xendit\HttpClient
 * @author   Ellen <ellen@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */

namespace Xendit\HttpClient;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Xendit\Exceptions\ApiException;
use Xendit\Exceptions\ApiValidationException;
use Xendit\Exceptions\ApiValidationExceptionErrors;
use Xendit\Xendit;

/**
 * Class GuzzleClient
 *
 * @category Class
 * @package  Xendit\HttpClient
 * @author   Ellen <ellen@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */
class GuzzleClient implements ClientInterface
{
    private static $_instance;

    protected $http;

    /**
     * XenditClient constructor
     */
    public function __construct()
    {
        if (Xendit::getHttpClient()) {
            $this->http = Xendit::getHttpClient();
        } else {
            $baseUri = strval(Xendit::$apiBase);
            $this->http = new Guzzle(
                [
                    'base_uri' => $baseUri,
                    'verify' => false,
                    'timeout' => 60
                ]
            );
        }
    }

    /**
     * Create Client instance
     *
     * @return GuzzleClient
     */
    public static function instance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Create a request to execute in _executeRequest
     *
     * @param string $method         request method
     * @param string $url            url
     * @param array  $defaultHeaders request headers
     * @param array  $params         parameters
     *
     * @return array
     * @throws ApiException
     */
    public function sendRequest($method, string $url, array $defaultHeaders, $params)
    {
        $method = strtoupper($method);

        $opts = [];

        $opts['method'] = $method;
        $opts['headers'] = $defaultHeaders;
        $opts['params'] = $params;

        $response = $this->_executeRequest($opts, $url);

        $rbody = $response[0];
        $rcode = $response[1];
        $rheader = $response[2];

        return [$rbody, $rcode, $rheader];
    }

    /**
     * Execute request
     *
     * @param array  $opts request options (headers, params)
     * @param string $url  request url
     *
     * @return array
     * @throws ApiException
     */
    private function _executeRequest(array $opts, string $url)
    {
        $headers = $opts['headers'];
        $params = $opts['params'];
        $apiKey = Xendit::$apiKey;
        $url = strval($url);
        try {
            if (count($params) > 0) {
                $response =  $this->http->request(
                    $opts['method'], $url, [
                        'auth' => [$apiKey, ''],
                        'headers' => $headers,
                        RequestOptions::JSON => $params
                    ]
                );
            } else {
                $response =  $this->http->request(
                    $opts['method'], $url, [
                        'auth' => [$apiKey, ''],
                        'headers' => $headers
                    ]
                );
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $rbody = json_decode($response->getBody()->getContents(), true);
            $rcode = $response->getStatusCode();
            $rheader = $response->getHeaders();

            self::_handleAPIError(
                array('body' => $rbody,
                      'code' => $rcode,
                      'header' => $rheader)
            );
        }

        $rbody = $response->getBody();
        $rcode = (int) $response->getStatusCode();
        $rheader = $response->getHeaders();

        return [$rbody, $rcode, $rheader];
    }

    /**
     * Handles API Error
     *
     * @param array $response response from GuzzleClient
     *
     * @return void
     * @throws ApiException
     */
    private static function _handleAPIError($response)
    {
        $rbody = $response['body'];

        $rhttp = strval($response['code']);
        $message = $rbody['message'];
        $rcode = $rbody['error_code'];

        if ($rcode === 'API_VALIDATION_ERROR') {
            $rawValidationErrors = $rbody['errors'] ?? [];
            $validationErrors    = [];

            foreach ($rawValidationErrors as $rawValidationError) {
                $validationError = new ApiValidationExceptionErrors(
                    $rawValidationError['path'],
                    $rawValidationError['message']
                );

                array_push($validationErrors, $validationError);
            }

            throw new ApiValidationException($message, $rhttp, $rcode, $validationErrors);
        }

        throw new ApiException($message, $rhttp, $rcode);
    }
}
