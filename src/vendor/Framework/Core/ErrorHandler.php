<?php

namespace Framework;

/**
 * Class ErrorHandler
 * @package Framework
 *
 * We inspect any errors that occur in the application
 * and display the corresponding exception
 */
class ErrorHandler
{
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }

        set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * The method handles exceptions caused by errors in the function error_reporting()
     * overridden by the function set_exception_handler()
     *
     * @param $e
     */
    public function exceptionHandler($e)
    {
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Exception code: ', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    /**
     * @param string $message
     * @param string $file
     * @param string $line
     */
    protected function logErrors($message = '', $file = '', $line = '')
    {
        error_log("[" . date('Y-m-d H:i:s') . "] Error text: {$message} | Error file: {$file} | Error line: {$line}\n--------------------+\n",
            3, ROOT . '/tmp/errors.log');
    }

    /**
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @param $responce
     */
    protected function displayError($errno, $errstr, $errfile, $errline, $response)
    {
        http_response_code($response);

        if ($response == 404 && !DEBUG || $response == 500 && !DEBUG) {
            require WWW . "/errors/{$response}.html";
            die;
        }
        elseif (DEBUG && $response != '') {
            require WWW . '/errors/debug_mode.php';
        }

        die;
    }
}