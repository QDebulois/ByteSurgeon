<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmModEnum: string
{
    case REGISTER = '11';

    case MEMORY_NO_OFFSET = '00';

    case MEMORY_OFFSET8 = '01';

    case MEMORY_OFFSET32 = '10';

    public static function fromStrbin(string $strbin): ?self
    {
        return self::tryFrom($strbin);
    }
}
