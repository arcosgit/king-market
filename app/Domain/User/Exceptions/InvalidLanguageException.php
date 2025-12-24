<?php

namespace App\Domain\User\Exceptions;

use DomainException;

final class InvalidLanguageException extends DomainException
{
    public static function notSupported(string $lang)
    {
        return new self("Language '{$lang}' is not supported. Use 'ru' or 'en'.");
    }
}
