<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Surgeon;

use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Enum\StructModelEnum;
use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;
use Qdebulois\ByteSurgeon\Struct\Struct;
use Qdebulois\ByteSurgeon\Struct\StructModelFactory;

class Surgeon
{

    private StructModelFactory $structModelFactory;

    public function __construct() {
        $this->structModelFactory = new StructModelFactory();
    }

    public function castBinToChars(string $binary): string {
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

    public function castBinToHex(string $binary): string {
        return implode(
            ' ',
            array_map(
                fn ($byte) => "0x".dechex($byte),
                unpack(StructTypeEnum::UINT8->value.'*', $binary),
            ),
        );
    }

    public function retrieveHeadStruct(string $binaries): Struct
    {
        $elfHeader = $this->structModelFactory->make(StructModelEnum::ELF_HEADER);
        $elfHeader->read($binaries);

        return $elfHeader;
    }

    public function retrieveSectionsNames(string $binaries) {
        $elfSectionHeader = $this->retrieveSectionStruct($binaries);

        $sectionNames = substr($binaries, $elfSectionHeader->sh_offset, $elfSectionHeader->sh_size);

        return $sectionNames;
    }

    public function retrieveSectionStruct(string $binaries, ?ElfSectionEnum $section = null): ?Struct
    {
        $elfHeader = $this->retrieveHeadStruct($binaries);

        $shstrtab_offset = $elfHeader->e_shoff + $elfHeader->e_shstrndx * $elfHeader->e_shentsize;

        $elfSectionHeader = $this->structModelFactory->make(StructModelEnum::ELF_SECTION_HEADER);
        $elfSectionHeader->read($binaries, $shstrtab_offset);

        if ($section === null) {
            return $elfSectionHeader;
        }

        $sectionNames = $this->retrieveSectionsNames($binaries);

        $isFound = false;
        $target = $section->value;
        for ($i = 0; $i < $elfHeader->e_shnum; ++$i) {
            $offset = $elfHeader->e_shoff + $i * $elfHeader->e_shentsize;

            $elfSectionHeader->read($binaries, $offset);

            $nameOffset     = $elfSectionHeader->sh_name;
            $nullTerminated = substr($sectionNames, $nameOffset);
            $sectionName    = strtok($nullTerminated, "\0");

            if ($target === $sectionName) {
                $isFound = true;

                break;
            }
        }

        return $isFound ? $elfSectionHeader : null;
    }

    public function extractSectionBin(string $binaries, ElfSectionEnum $section): ?string
    {
        $elfSectionHeader = $this->retrieveSectionStruct($binaries, $section);

        return $elfSectionHeader ? substr($binaries, $elfSectionHeader->sh_offset, $elfSectionHeader->sh_size) : null;
    }

    public function retrieveSectionOffset(string $binaries, ElfSectionEnum $section): ?int
    {
        $elfSectionHeader = $this->retrieveSectionStruct($binaries, $section);

        return $elfSectionHeader ? $elfSectionHeader->sh_offset : null;
    }

    public function writeBytes($handler, string $binaries, elfSectionEnum $section, int $offset, int ...$bytes): bool
    {
        $elfSectionOffset = $this->retrieveSectionOffset($binaries, $section);

        rewind($handler);

        fseek($handler, $elfSectionOffset + $offset);

        fwrite($handler, pack(StructTypeEnum::UINT8->value.'*', ...$bytes));

        fflush($handler);

        return true;
    }
}
