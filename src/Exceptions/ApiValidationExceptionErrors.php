<?php

/**
 * ApiValidationException.php
 * php version 7.2.0
 *
 * @category Exception
 * @package  Xendit\Exceptions
 * @author   Rifad <rifad@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */

namespace Xendit\Exceptions;

/**
 * Class ApiValidationExceptionErrors
 *
 * @category Class
 * @package  Xendit\Exceptions
 * @author   Rifad <rifad@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */
class ApiValidationExceptionErrors
{
    protected $path;

    protected $message;

    /**
     * ApiValidationExceptionErrors constructor.
     *
     * @param string $path      corresponds to the location of the invalid parameter in the request
     * @param string $message   corresponds to the reason why the parameter is invalid
     */
    public function __construct($path, $message)
    {
        $this->path    = $path;
        $this->message = $message;
    }

    /**
     * Get the location of the invalid parameter in the request
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the reason why the parameter is invalid
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function __toString()
    {
        return $this->path . ': ' . $this->message;
    }
}
