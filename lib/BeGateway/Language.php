<?php

namespace BeGateway;

/**
 * Class Language
 *
 * @package BeGateway
 */
class Language
{
    /**
     * @return string[]
     */
    public static function getSupportedLanguages()
    {
        return array(
            'en', 'es', 'tr', 'de', 'it', 'ru', 'zh', 'fr', 'da', 'fi', 'no', 'pl', 'sv', 'ja', 'be', 'ka',
        );
    }

    /**
     * @return string
     */
    public static function getDefaultLanguage()
    {
        return 'en';
    }
}

