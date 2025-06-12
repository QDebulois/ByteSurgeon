<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

/** Only after OpcodeEnum::SYSCALL_PREFIX (0x0F) */
enum OpcodeSyscallEnum: int
{
    /** Fast system call x86_64 */
    case SYSCALL = 0x05;

    /** Fast system call x86 */
    case SYSENTER = 0x34;

    /** Fast return from syscall x86_64 */
    case SYSRET = 0x07;

    /** Fast return from syscall x86 */
    case SYSEXIT = 0x35;

    public static function fromInt(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
