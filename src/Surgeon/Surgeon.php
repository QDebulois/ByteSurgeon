<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Surgeon;

use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;

class Surgeon
{
    public function translate (string $binary): string {
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $str = [];

        foreach ($binary as $byte) {
            if ($byte < 32 || $byte > 127) {
                $str[] = '0x'.dechex($byte);
                continue;
            }

            $str[] = chr($byte);
        }

        return implode(' ', $str);
    }
}
