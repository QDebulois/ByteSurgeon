<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum InstructionEnum: int
{
    case ENDBR64 = 0;

    case NOP = 1;

    case RETN = 2;

    case RETF = 3;
}
