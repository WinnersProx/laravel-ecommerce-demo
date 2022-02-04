<?php

namespace App\Exceptions;

class GeneralException extends \Exception
{

    protected $message;
    public $details;
    private $scope = 'local';
    public $reason = null;

    public $errors = null;

    public function __construct($message, $statusCode, $exception = null, $details = null)
    {
        parent::__construct($message, $statusCode);
        $this->details = $details;
    }

    /**
     * Throw exceptions with a scope
     * @param string $scope the scope of the exception
     * to catch external services exceptions
     */
    public function withScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get the formatted exception to construct responses
     */
    public function getFormatted()
    {
        $payload = ['success' => false, 'message' => $this->message];

        if ($this->scope) $payload['scope'] = $this->scope;

        if ($this->reason) $payload['reason'] = $this->reason;

        if ($this->details) $payload['details'] = $this->details;

        if ($this->errors) $payload['errors'] = $this->errors;

        return $payload;
    }

    public function withReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    public function withDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    public function withErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    public function throw()
    {
        throw $this;
    }
}
