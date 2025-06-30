<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Instruction;

use Qdebulois\ByteSurgeon\Enum\InstructionEnum;

class Instruction
{
    /** @return int[] */
    public function getInstruction(InstructionEnum $instructionEnum): array
    {
        return match ($instructionEnum) {
            InstructionEnum::ENDBR64 => [0xF3, 0x0F, 0x1E, 0xFA],
            InstructionEnum::NOP     => [0x90],
            InstructionEnum::RETN    => [0xC3],
            InstructionEnum::RETF    => [0xCB],
            default                  => throw new \Exception('Unknown instruction'),
        };
    }
}
