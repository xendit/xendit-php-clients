<?php

/**
 * ApiValidationExceptionErrors.php
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
 * Class ApiValidationException
 *
 * @category Exception
 * @package  Xendit\Exceptions
 * @author   Rifad <rifad@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */
class ApiValidationException extends ApiException
{
    protected $validationErrors;

    /**
     * ApiValidationException constructor.
     *
     * @param string $message   corresponds to message field in Xendit's HTTP error
     * @param string $code      corresponds to http status in Xendit's HTTP response
     * @param string $errorCode corresponds to error_code field in Xendit's HTTP error
     * @param array $errors     corresponds to the array of validation errors in Xendit's HTTP response body
     */
    public function __construct($message, $code, $errorCode, $errors)
    {
        parent::__construct($message, $code, $errorCode);

        $this->validationErrors = $errors;
    }

    /**
     * Get the list of validation errors
     *
     * @return ApiValidationExceptionErrors[]
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
