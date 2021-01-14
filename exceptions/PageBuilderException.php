<?php namespace MG\PageBuilder\Exceptions;

use Symfony\Component\HttpFoundation\Request;

/**
 * MG Page Builder Base Exception class
 */
class PageBuilderException extends \Exception
{
    /**
     * The HTTP status code for this exception that should be sent in the response
     */
    public $httpStatusCode = 400;

    /**
     * The exception type
     */
    public $errorType = '';

    /**
     * Parameter eventually passed to Exception
     */
    public $parameter = '';

    /**
     * Throw a new exception
     *
     * @param string $msg Exception Message
     */
    public function __construct($msg = 'An error occured')
    {
        parent::__construct($msg);
    }

    /**
     * Return parameter if set
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Get all headers that have to be send with the error response
     *
     * @return array Array with header values
     */
    public function getHttpHeaders()
    {
        $headers = [];
        switch ($this->httpStatusCode) {
            case 401:
                $headers[] = 'HTTP/1.1 401 Unauthorized';
                break;
            case 500:
                $headers[] = 'HTTP/1.1 500 Internal Server Error';
                break;
            case 501:
                $headers[] = 'HTTP/1.1 501 Not Implemented';
                break;
            case 400:
            default:
                $headers[] = 'HTTP/1.1 400 Bad Request';
                break;
        }
        // @codeCoverageIgnoreEnd
        return $headers;
    }

}
