<?php

use App\Exceptions\GeneralException;

if (!function_exists('triggerException')) {
    function triggerException($message, $statusCode = 400)
    {
        return new GeneralException($message, $statusCode);
    }
}


if (!function_exists('throwIf')) {
    function throwIf(bool $condition, string $message, $statusCode = 400)
    {
        if (!$condition) return;

        return (new GeneralException($message, $statusCode))->throw();
    }
}
