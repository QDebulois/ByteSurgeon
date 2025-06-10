<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmModEnum: string
{
    case NO_DISPLACEMENT = '00';

    case DISPLACEMENT8 = '01';

    case DISPLACEMENT32 = '10';

    case REGISTER = '11';

    public static function fromStrbin(string $strbin): ?self
    {
        return self::tryFrom($strbin);
    }
}
