<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmRmEnum: string
{
    // Accumulator, 32 bits
    case EAX = '000';

    // General-purpose counter register, 32-bit (often used for loops)
    case ECX = '001';

    // Data register, 32-bit (also used for I/O)
    case EDX = '010';

    // General-purpose base register, 32-bit (often used as a base pointer)
    case EBX = '011';

    // Stack Pointer, 32-bit
    case ESP = '100';

    // Base Pointer, 32-bit (often used to access local variables)
    case EBP = '101';

    // Source Index, used for string/array/memory operations
    case ESI = '110';

    // Destination Index, used for string/array/memory operations
    case EDI = '111';

    public static function fromStrbin(string $strbin): ?self
    {
        return self::tryFrom($strbin);
    }
}
