<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmRegEnum: string
{
    case ADD = '000';

    case OR = '001';

    case ADC = '010'; // Add with carry

    case SBB = '011'; // Sub with borrow

    case AND = '100';

    case SUB = '101';

    case XOR = '110';

    case CMP = '111';

    public static function fromStrbin(string $strbin): ?self
    {
        return self::tryFrom($strbin);
    }
}
