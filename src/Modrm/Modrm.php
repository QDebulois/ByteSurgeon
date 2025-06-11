<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Modrm;

use Qdebulois\ByteSurgeon\Enum\ModrmModEnum;
use Qdebulois\ByteSurgeon\Enum\ModrmRegEnum;
use Qdebulois\ByteSurgeon\Enum\ModrmRmEnum;

class Modrm
{
    private ?ModrmModEnum $mod = null;
    private ?ModrmRegEnum $reg = null;
    private ?ModrmRmEnum $rm   = null;

    public function set(ModrmModEnum $mod, ModrmRegEnum $reg, ModrmRmEnum $rm) {
        $this->mod = $mod;
        $this->reg = $reg;
        $this->rm  = $rm;
    }

    public function getMod()
    {
        return $this->mod;
    }

    public function getReg()
    {
        return $this->reg;
    }

    public function getRm()
    {
        return $this->rm;
    }

    public function read(int $byte)
    {
        $strbin = sprintf('%08b', $byte);

        $this->mod = ModrmModEnum::fromStrbin(substr($strbin, 0, 2));
        $this->reg = ModrmRegEnum::fromStrbin(substr($strbin, 2, 3));
        $this->rm  = ModrmRmEnum::fromStrbin(substr($strbin, 5, 3));
    }

    public function castToInt(): int
    {
        return bindec(sprintf('%b', $this->mod).sprintf('%b', $this->reg).sprintf('%b', $this->rm));
    }
}
