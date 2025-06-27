<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum VexEnum: int
{
    case VEX_SHORT = 0xC5;

    case VEX_LONG = 0xC4;
}
