<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum OpcodeEnum: int
{
    // add/sub/cmp avec immediate 8-bit
    case ARITHMETIC_IMM8 = 0x83;

    // mov registre 8-bit immediate
    case MOV_REG_IMM8 = 0xB0;

    // mov registre 32-bit immediate (ex: B8+rd)
    case MOV_REG_IMM32 = 0xB8;

    // add reg/mem to reg
    case ADD_REG_MEM = 0x01;

    // add reg/mem to reg (variant)
    case ADD_REG_MEM_TO_REG = 0x03;

    // jump short relative 8-bit
    case JMP_REL8 = 0xEB;

    // jump near relative 32-bit
    case JMP_REL32 = 0xE9;

    // interrupt (ex: int 0x80 pour syscall)
    case INT = 0xCD;

    // préfixe pour instructions étendues (ex: syscall, sysenter)
    case SYSCALL_PREFIX = 0x0F;

    public static function fromInt(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
