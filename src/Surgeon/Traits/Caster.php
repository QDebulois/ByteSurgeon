<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Surgeon\Traits;

use Qdebulois\ByteSurgeon\Enum\AsciiEnum;
use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;
use Qdebulois\ByteSurgeon\Surgeon\Surgeon;

/**
 * @phpstan-require-extends Surgeon
 */
trait Caster
{
    public function castByteToStrbin(int $byte): string
    {
        return sprintf('%08b', $byte);
    }

    public function castByteToHex(int $byte): string
    {
        return sprintf('%02X', $byte);
    }

    public function castBinToInt(string $binary): string
    {
        /** @var Surgeon $this */
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $delimiter = ' ';
        $output    = [];
        $ints      = [];
        foreach ($binary as $byte) {
            $ints[] = $byte;

            if (count($ints) > $this->maxColPerLine) {
                $output[] = implode($delimiter, $ints);

                $ints = [];
            }
        }

        $output[] = implode($delimiter, $ints);

        return implode("\n", $output);
    }

    public function castBinToChars(string $binary): string
    {
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $delimiter = '';
        $output    = [];
        $asciis    = [];
        foreach ($binary as $byte) {
            $asciis[] = AsciiEnum::fromByte($byte);

            if (count($asciis) > $this->maxColPerLine) {
                $output[] = implode(
                    $delimiter,
                    array_map(fn (AsciiEnum $ascii) => $ascii->toChar(), $asciis)
                );

                $asciis = [];
            }
        }

        $output[] = implode(
            $delimiter,
            array_map(fn (AsciiEnum $ascii) => $ascii->toChar(), $asciis)
        );

        return implode("\n", $output);
    }

    public function castBinToHex(string $binary): string
    {
        /** @var Surgeon $this */
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $delimiter = ' ';
        $output    = [];
        $hexs      = [];
        foreach ($binary as $byte) {
            $hexs[] = $this->castByteToHex($byte);

            if (count($hexs) > $this->maxColPerLine) {
                $output[] = implode($delimiter, $hexs);

                $hexs = [];
            }
        }

        $output[] = implode($delimiter, $hexs);

        return implode("\n", $output);
    }

    public function castBinToStrbin(string $binary): string
    {
        /** @var Surgeon $this */
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $delimiter = ' ';
        $output    = [];
        $strbins   = [];
        foreach ($binary as $byte) {
            $strbins[] = $this->castByteToStrbin($byte);

            if (count($strbins) > $this->maxColPerLine) {
                $output[] = implode($delimiter, $strbins);

                $strbins = [];
            }
        }

        $output[] = implode($delimiter, $strbins);

        return implode("\n", $output);
    }
}
