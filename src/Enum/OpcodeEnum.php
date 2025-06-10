<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum OpcodeEnum: int
{
    case ARITHMETIC_IMM8 = 0x83;

    public static function fromInt(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
