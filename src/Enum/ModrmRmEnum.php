<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmRmEnum: int
{
    /** Accumulator, 32 bits */
    case EAX = 0b00000000;

    /** General-purpose counter register, 32-bit (often used for loops) */
    case ECX = 0b00000001;

    /** Data register, 32-bit (also used for I/O) */
    case EDX = 0b00000010;

    /** General-purpose base register, 32-bit (often used as a base pointer) */
    case EBX = 0b00000011;

    /** Stack Pointer, 32-bit */
    case ESP = 0b00000100;

    /** Base Pointer, 32-bit (often used to access local variables) */
    case EBP = 0b00000101;

    /** Source Index, used for string/array/memory operations */
    case ESI = 0b00000110;

    /** Destination Index, used for string/array/memory operations */
    case EDI = 0b00000111;

    public static function fromByte(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
