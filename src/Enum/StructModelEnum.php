<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum StructModelEnum: int
{
    case ELF_HEADER = 0;

    case ELF_PROGRAM_HEADER = 1;

    case ELF_SECTION_HEADER = 2;
}
