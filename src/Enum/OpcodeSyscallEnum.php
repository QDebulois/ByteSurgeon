<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

/**
 * After 0x0F
 */
enum OpcodeSyscallEnum: int
{
    case SYSCALL = 0x05;

    case SYSENTER = 0x34;

    public static function fromInt(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
