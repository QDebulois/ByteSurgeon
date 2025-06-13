<?php

declare(strict_types=1);

namespace ByteSurgeon\Enum;

enum OpcodeLegacyPrefixEnum: int
{
    // Prefix group 1

    case LOCK = 0xF0;

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

    // Prefix group 3

    case OPERAND_SIZE = 0x66;

    // Prefix group 4

    case ADDRESS_SIZE = 0x67;
}
