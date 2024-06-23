<?php
// disclaimer stuff
namespace Docparser;

use \GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class DocparserApiRequest
 *
 * provides a first layer of abstraction over Guzzle
 * transform responses from JSON and catches Exceptions to be rethrown
 * @see DocparserExceptionHandler
 *
 * @internal
 * @package Docparser
 */
class DocparserApiRequest
{

    /**
     * @var Client
     */
    private $guzzle;

    private $apiToken;

    private $host;

    /**
     * DocparserApiRequest constructor.
     * @param $host
     * @param $apiToken
     */
    public function __construct($host, $apiToken)
    {
        $this->host = $host;
        $this->apiToken = $apiToken;

        $this->guzzle = new Client([
            'base_uri' => $host,
            'timeout' => 10,
            'headers' => [
                'api_key' => $apiToken
            ]
        ]);

        return $this;
    }

    /**
     * @param $endpoint
     * @param array $payload
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function makeGetRequest($endpoint, $payload = [])
    {
        try {
            $response = $this->guzzle->get($endpoint, [
                'query' => $payload,
            ]);
        } catch (ClientException $e) {
            DocparserExceptionHandler::rethrow($e);
            return false;
        }

        $response = (string)$response->getBody();
        if (self::isJson($response)) {
            $response = json_decode($response, true);
        }

        return $response;
    }

    /**
     * @param $endpoint
     * @param $payload
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function makePostRequest($endpoint, $payload)
    {
        try {
            $response = $this->guzzle->post($endpoint, [
                'form_params' => $payload,
            ]);
        } catch (ClientException $e) {
            DocparserExceptionHandler::rethrow($e);
            return false;
        }

        $response = (string)$response->getBody();
        if (self::isJson($response)) {
            $response = json_decode($response, true);
        }

        return $response;
    }

    /**
     * this method is somewhat purpose-built for the specific endpoints
     * of Docparser. This is why the remote id parameter is included here,
     * when maybe it shouldn't be in this class at all
     *
     * @param $endpoint
     * @param $fileContent
     * @param null $remoteId
     * @param null $filename optional filename
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function uploadDocument($endpoint, $fileContent, $remoteId = null, $filename = null)
    {
        try {
            $request = [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => $fileContent,
                        'filename' => ($filename) ? $filename : null
                    ],
                    [
                        'name' => 'remote_id',
                        'contents' => $remoteId
                    ]
                ]
            ];

            $response = $this->guzzle->request('POST', $endpoint, $request);
        } catch (ClientException $e) {
            DocparserExceptionHandler::rethrow($e);
            return false;
        }

        $response = (string)$response->getBody();
        if (self::isJson($response)) {
            $response = json_decode($response, true);
        }

        return $response;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
