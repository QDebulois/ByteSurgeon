<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Dto;

use Qdebulois\ByteSurgeon\Enum\OpcodeEnum;
use Qdebulois\ByteSurgeon\Enum\OpcodeSyscallEnum;
use Qdebulois\ByteSurgeon\Modrm\Modrm;

class FoundOpcodeDto
{
    public int $offset;
    public OpcodeEnum $opcode;
    public ?OpcodeSyscallEnum $opcodeSyscall = null;
    public ?Modrm $modrm = null;
    /* @var int[] */
    public array $values = [];
}
