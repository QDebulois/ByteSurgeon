<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmModEnum: string
{
    case DISPLACEMENT = '01';

    case REGISTER = '11';

    public static function fromStrbin(string $strbin): ?self
    {
        return self::tryFrom($strbin);
    }
}
