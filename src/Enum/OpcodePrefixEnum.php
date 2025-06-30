<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum OpcodePrefixEnum: int
{
    // Prefix group 1

    case LOCK = 0xF0;

    /** Repete jusqu'a echec */
    case REPNE_REPNZ = 0xF2;

    /**  REP or REPE/REPZ prefix */
    case REP = 0xF3;

    // Prefix group 2

    /** segment override */
    case CS = 0x2E;

    /** segment override */
    case SS = 0x36;

    /** segment override */
    case DS = 0x3E;

    /** segment override */
    case ES = 0x26;

    /** segment override */
    case FS = 0x64;

    /** segment override */
    // case FS = 0x65; // TODO

    // case BRANCH_HINT_1 = 0x2E; // TODO
    // case BRANCH_HINT_2 = 0x3E; // TODO

    // Prefix group 3

    /** Passe de 32-bit -> 16-bit operand size */
    case OPERAND_SIZE_OVERRIDE = 0x66;

    // Prefix group 4

    case ADDRESS_SIZE_OVERRIDE = 0x67;

    public static function map() {
        $map = [];

        foreach (self::cases() as $case) {
            $map[$case->value] = $case->name;
        }

        return $map;
    }
}
