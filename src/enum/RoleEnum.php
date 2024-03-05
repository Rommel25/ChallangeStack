<?php

namespace App\enum;

class RoleEnum
{
    const ADMIN = 'ADMIN';
    const TEACHER = 'TEACHER';
    const USER = 'USER';

    public static array $types = [
        self::ADMIN => 'Administrateur',
        self::TEACHER => 'Formateur',
        self::USER => 'Etudiant'
    ];

    public static function getType($key): string
    {
        if (!isset(self::$types[$key])) {
            return "Role inconnu ($key)";
        }

        return self::$types[$key];
    }

    public static function getChoices(): array
    {
        return array_flip(self::$types);
    }

}