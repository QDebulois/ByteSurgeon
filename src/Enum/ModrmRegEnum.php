<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmRegEnum: string
{
    case ADD = '000';

    case SUB = '101';

    public static function fromStrbin(string $strbin): ?self
    {
        return self::tryFrom($strbin);
    }
}
