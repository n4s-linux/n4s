<?php
// disclaimer stuff

namespace Docparser;

use Exception;
use GuzzleHttp\Exception\ClientException;

/**
 * this tries to translate Guzzle exceptions into more useful responses
 * where possible
 *
 * Class DocparserExceptionHandler
 * @package Docparser
 */
class DocparserExceptionHandler
{

    /**
     * @param $e ClientException
     * @throws DocparserAPIException
     * @throws Exception
     */
    public static function rethrow($e)
    {
        throw $e;
    }
}
