<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

/**
 * After 0x0F
 */
enum OpcodeSyscallEnum: int
{
    case SYSCALL = 0x05; // Appel système rapide AMD64 (x86_64)

    case SYSENTER = 0x34; // Appel système rapide Intel (x86 32-bit)

    CASE SYSEXIT =	0x35;	// Retour rapide depuis syscall (Intel)

    CASE SYSRET	 = 0x07; //	Retour rapide syscall (AMD64)

    public static function fromInt(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
