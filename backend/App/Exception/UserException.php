<?php
namespace App\Exception;

class UserException extends \Exception
{
    const INVALID_SESSION_CODE = 1;
    const USER_NOT_FOUND = 2;
    const AUTHENTICATION_FAILURE = 3;

    private $erroCode;
    private $content;
    public function __construct(int $erroCode, string $message, array $content)
    {
        parent::__construct($message);
        $this->erroCode = $erroCode;
        $this->content = $content;
    }

    public function getErrorCode()
    {
        return $this->erroCode;
    }
}
