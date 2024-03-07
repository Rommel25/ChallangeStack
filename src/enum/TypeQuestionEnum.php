<?php

namespace App\enum;

class TypeQuestionEnum
{

    const OPEN = 'OPEN';
    const CLOSE = 'CLOSE';
    const INTERVAL = 'INTERVAL';

    public static array $types = [
        self::OPEN => 'Question ouvetre',
        self::CLOSE => 'Question fermé (OUI/NON)',
        self::INTERVAL => 'Intervalle entre 0 et 5'
    ];

    public static function getType($key): string
    {
        if (!isset(self::$types[$key])) {
            return "Type de question inconnu ($key)";
        }

        return self::$types[$key];
    }

    public static function getChoices(): array
    {
        return array_flip(self::$types);
    }


}