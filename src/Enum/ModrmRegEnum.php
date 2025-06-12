<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ModrmRegEnum: int
{
    case ADD = 0b00000000;

    case OR = 0b00000001;

    /** Add with carry */
    case ADC = 0b00000010;

    /** Sub with borrow */
    case SBB = 0b00000011;

    case AND = 0b00000100;

    case SUB = 0b00000101;

    case XOR = 0b00000110;

    case CMP = 0b00000111;

    public static function fromByte(int $byte): ?self
    {
        return self::tryFrom($byte);
    }
}
