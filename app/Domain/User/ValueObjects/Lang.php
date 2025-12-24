<?php
namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidLanguageException;

final class Lang
{
    private const AVAILABLE_LANGS = ['ru', 'en'];

    public function __construct(private string $lang)
    {
        if(!\in_array($lang, self::AVAILABLE_LANGS, true)){
            throw InvalidLanguageException::notSupported($lang);
        }
    }

    public function lang()
    {
        return $this->lang;
    }
}
