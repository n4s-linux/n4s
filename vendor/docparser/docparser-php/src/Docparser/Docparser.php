<?php
// disclaimer stuff
namespace Docparser;

use DateTime;

/**
 * Class Docparser
 * @package Docparser
 *
 * this class provides the developer-facing interface to the Docparser API
 *
 */
class Docparser
{
    /**
     * base URL
     */
    const API_URL = 'https://api.docparser.com/v1/';

    /**
     * allowed types for getResultsByParser
     */
    const ALLOWED_LIST_TYPES = [
        'last_uploaded',
        'uploaded_after',
        'processed_after',
    ];

    private $apiToken;

    /**
     * Docparser constructor.
     *
     * @api
     * @param String $apiToken
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * this method is used for testing authentication.
     *
     * @api
     *  @return boolean indicates validity of auth credentials
     * @throws DocparserApiException
     */
    public function ping()
    {
        $request = $this->createRequest();

        if ((bool) $request->makeGetRequest('ping')) {
            return true;
        }

        return false;
    }

    /**
     * retrieves the created document parsers
     *
     * @api
     *  @return array
     * @throws DocparserApiException
     */
    public function getParsers()
    {
        $request = $this->createRequest();
        $response = $request->makeGetRequest('parsers');

        return $response;
    }

    /**
     * fetches all of the model layouts associated with a given parser
     *
     * @api
     * @param $parserId
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function getParserModelLayouts($parserId)
    {
        $request = $this->createRequest();
        $endpoint = 'parser/models/' . $parserId;

        $response = $request->makeGetRequest($endpoint);

        return $response;
    }
    
    /**
     * uploads documents from a given file path
     *
     * @api
     * @param $parserId
     * @param $filePath
     * @param null $remoteId
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws DocparserApiException
     */
    public function uploadDocumentByPath($parserId, $filePath, $remoteId = null)
    {
        if (!file_exists($filePath)) {
            throw new DocparserApiException("No such file.");
        }

        if (is_dir($filePath)) {
            throw new DocparserApiException("Passed a directory, expected file.");
        }

        return $this->uploadDocumentByContents($parserId, fopen($filePath, 'r'), $remoteId);
    }

    /**
     * uploads document by content or file handle
     *
     * @api
     * @param $parserId
     * @param $file
     * @param null $remoteId
     * @param null $filename optional filename
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws DocparserApiException
     */
    public function uploadDocumentByContents($parserId, $file, $remoteId = null, $filename = null)
    {
        if (empty($file)) {
            throw new DocparserApiException("Given file (handle) is empty");
        }

        $request = $this->createRequest();
        $endpoint = 'document/upload/' . $parserId;

        $response = $request->uploadDocument($endpoint, $file, $remoteId, $filename);

        return $response;
    }

    /**
     * fetches a document from a publicly accessible URL
     *
     * @api
     * @param $parserId
     * @param $url
     * @param null $remoteId
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function fetchDocumentFromURL($parserId, $url, $remoteId = null)
    {
        $request = $this->createRequest();
        $endpoint = 'document/fetch/' . $parserId;

        $response = $request->makePostRequest($endpoint, [
            'url' => $url,
            'remote_id' => $remoteId
        ]);

        return $response;
    }

    /**
     * retrieve results by parser + document ID
     *
     * @api
     * @param $parserId
     * @param $documentId
     * @param string $format
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function getResultsByDocument($parserId, $documentId, $format = 'object')
    {
        $request = $this->createRequest();
        $endpoint = 'results/' . $parserId . '/' . $documentId;

        $response = $request->makeGetRequest($endpoint, [
            'format' => $format
        ]);

        return $response;
    }

    /**
     * retrieves all results of a particular parser
     *
     * the parameters limit and list are switched around compared to
     * the order in the devdocs, because you're more likely to need the limit parameter
     *
     * *NOTE:* usage of this method is not recommended - it's better to
     * use Docparser Webhooks
     * @see https://dev.docparser.com/?shell#get-multiple-data-sets
     *
     * @api
     *
     * @param $parserId
     *
     * @param array $options
     * optionally containing the keys:
     * format, limit, list, date, includeProcessingQueue
     *
     * @return bool|mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function getResultsByParser(
        $parserId,
        $options = []
    ) {
        $format = (isset($options['format'])) ? $options['format'] : 'object';
        $limit = (isset($options['limit'])) ? $options['limit'] : 100;

        $list = 'last_uploaded';

        $remoteId = null;
        if (isset($options['remoteId'])) {
            $remoteId = $options['remoteId'];
        }

        if (isset($options['list'])) {
            $list = self::translateListValue($options['list']);
        }

        $date = self::translateDateValue(new DateTime());
        if (isset($options['date'])) {
            $date = self::translateDateValue($options['date']);
        }

        $includeProcessingQueue = false;
        if (isset($options['includeProcessingQueue'])) {
            $includeProcessingQueue = (bool) $options['includeProcessingQueue'];
        }

        $request = $this->createRequest();
        $endpoint = 'results/' . $parserId;

        $response = $request->makeGetRequest($endpoint, [
            'format' => $format,
            'list' => $list,
            'limit' => $limit,
            'date' => $date,
            'remote_id' => $remoteId,
            'include_processing_queue' => $includeProcessingQueue
        ]);

        return $response;
    }

    /**
     * creates a request object
     *
     * @interal
     *  @return DocparserApiRequest
     */
    protected function createRequest()
    {
        return new DocparserApiRequest(self::API_URL, $this->apiToken);
    }

    /**
     * @param $listParam
     * @return string
     */
    private static function translateListValue($listParam = null)
    {
        if (!in_array($listParam, self::ALLOWED_LIST_TYPES)) {
            return 'last_uploaded';
        }

        return $listParam;
    }

    /**
     * @param $date
     * (DateTime instance or acceptable DateTime constructor parameter
     *
     * @return string (date as ISO 8601)
     */
    private static function translateDateValue($date = null)
    {
        if (!$date) {
            $date = '1970-01-01T00:00:00+0000';
        }

        if ($date instanceof DateTime) {
            return $date->format('c');
        } else {
            $date = new DateTime($date);
            return $date->format('c');
        }
    }
}
