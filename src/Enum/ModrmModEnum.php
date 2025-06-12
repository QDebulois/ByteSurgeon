<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmModEnum: int
{
    case MEMORY_NO_OFFSET = 0b00000000;

    case MEMORY_OFFSET8 = 0b00000001;

    case MEMORY_OFFSET32 = 0b00000010;

    case REGISTER = 0b00000011;

    public static function fromByte(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
