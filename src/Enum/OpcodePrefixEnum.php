<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum OpcodePrefixEnum: int
{
    // Groupe 1 : Verrouillage/Répétition

    case LOCK = 0xF0;

    /** Repete jusqu'a echec */
    case REPNE_REPNZ = 0xF2;

    case REP = 0xF3;

    // Groupe 2 : Surcharge de segment

    case CS = 0x2E;

    case SS = 0x36;

    case DS = 0x3E;

    case ES = 0x26;

    case FS = 0x64;

    case GS = 0x65;

    // Groupe 3 : Surcharge de taille d'opérande

    case OPERAND_SIZE_OVERRIDE = 0x66;

    // Groupe 4 : Surcharge de taille d'adresse

    case ADDRESS_SIZE_OVERRIDE = 0x67;

    // Option : gérer les doubles sens (branche/segment)

    case BRANCH_HINT_NOT_TAKEN = 0x2E; // = CS en contexte segment

    case BRANCH_HINT_TAKEN = 0x3E;     // = DS en contexte segment

    public static function map()
    {
        $map = [];

        foreach (self::cases() as $case) {
            $map[$case->value] = $case->name;
        }

        return $map;
    }
}
