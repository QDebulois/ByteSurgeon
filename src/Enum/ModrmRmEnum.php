<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmRmEnum: string
{
    case EAX = '000';

    public static function fromStrbin(string $strbin): ?self
    {
        return self::tryFrom($strbin);
    }
}
