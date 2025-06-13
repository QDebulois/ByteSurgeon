<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Modrm;

use Qdebulois\ByteSurgeon\Enum\ModrmModEnum;
use Qdebulois\ByteSurgeon\Enum\ModrmRegEnum;
use Qdebulois\ByteSurgeon\Enum\ModrmRmEnum;

class Modrm
{
    private bool $isPopulated  = false;
    private ?ModrmModEnum $mod = null;
    private ?ModrmRegEnum $reg = null;
    private ?ModrmRmEnum $rm   = null;

    public function set(ModrmModEnum $mod, ModrmRegEnum $reg, ModrmRmEnum $rm): void
    {
        $this->mod = $mod;
        $this->reg = $reg;
        $this->rm  = $rm;
    }

    public function getMod(): ?ModrmModEnum
    {
        return $this->mod;
    }

    public function getReg(): ?ModrmRegEnum
    {
        return $this->reg;
    }

    public function getRm(): ?ModrmRmEnum
    {
        return $this->rm;
    }

    public function read(int $byte): void
    {
        $maskMod   = 0b11000000;
        $this->mod = ModrmModEnum::fromByte(($byte & $maskMod) >> 6);

        $maskReg   = 0b00111000;
        $this->reg = ModrmRegEnum::fromByte(($byte & $maskReg) >> 3);

        $maskRm   = 0b00000111;
        $this->rm = ModrmRmEnum::fromByte($byte & $maskRm);

        $this->isPopulated = true;
    }

    /** The SIB byte specifies scaled index and base registers for complex memory addressing. */
    public function hasSib(): bool
    {
        if (!$this->isPopulated) {
            throw new \Exception('Modrm is not populated');
        }

        return ModrmModEnum::REGISTER !== $this->mod && ModrmRmEnum::ESP === $this->rm;
    }

    public function getValueLength(): int
    {
        if (!$this->isPopulated) {
            throw new \Exception('Modrm is not populated');
        }

        if ($this->mod === ModrmModEnum::REGISTER) {
            return 0;
        }

        $length = 1;

        if ($this->hasSib()) {
            ++$length;
        }

        if (ModrmModEnum::MEMORY_OFFSET8 === $this->mod) {
            ++$length;
        } elseif (ModrmModEnum::MEMORY_OFFSET32 === $this->mod) {
            $length += 4;
        } elseif (ModrmModEnum::MEMORY_NO_OFFSET === $this->mod && ModrmRmEnum::EBP === $this->rm) {
            $length += 4;
        }

        return $length;
    }

    public function castToByte(): int
    {
        return ($this->mod->value << 6) | ($this->reg->value << 3) | $this->rm->value;
    }
}
