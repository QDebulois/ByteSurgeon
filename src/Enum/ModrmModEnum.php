<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmModEnum: int
{
    /** Memory access with no displacement (unless rm === ModrmRmEnum::EBP , then 32-bit disp follows) */
    case MEMORY_NO_OFFSET = 0b00000000;

    /** Memory access with 8-bit signed displacement */
    case MEMORY_OFFSET8 = 0b00000001;

    /** Memory access with 32-bit displacement */
    case MEMORY_OFFSET32 = 0b00000010;

    /** Direct register-to-register operation, no memory access */
    case REGISTER = 0b00000011;

    public static function fromByte(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
