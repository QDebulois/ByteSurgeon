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
    private mixed $handler    = null;
    private ?string $filename = null;
    private ?int $filesize = null;
    private StructModelFactory $structModelFactory;

    public function __construct()
    {
        $this->structModelFactory = new StructModelFactory();
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function open(string $filename): void
    {
        $this->filename = $filename;
        $this->handler  = fopen($filename, 'r+b');

        if (false === $this->handler) {
            throw new \Exception('Failed to open file');
        }

        $this->filesize = filesize($filename);
    }

    public function close(): void
    {
        fclose($this->handler);
    }

    public function castBinToChars(string $binary): string
    {
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $str        = [];
        $line       = [];
        $maxPerLine = 30;
        foreach ($binary as $byte) {
            if ($byte < 0x20 || $byte > 0x7F) {
                $hex    = dechex($byte);
                $line[] = '0x'.(1 === strlen($hex) ? '0'.$hex : $hex);
            } elseif (0x20 === $byte) {
                $line[] = "'  '";
            } else {
                $line[] = '   '.chr($byte);
            }

            if (count($line) > $maxPerLine) {
                $str[] = implode(' ', $line);

                $line = [];
            }
        }

        $str[] = implode(' ', $line);

        return implode("\n", $str);
    }

    public function castBinToHex(string $binary): string
    {
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $str        = [];
        $line       = [];
        $maxPerLine = 30;
        foreach ($binary as $byte) {
            $hex    = dechex($byte);
            $line[] = '0x'.(1 === strlen($hex) ? '0'.$hex : $hex);

            if (count($line) > $maxPerLine) {
                $str[] = implode(' ', $line);

                $line = [];
            }
        }

        $str[] = implode(' ', $line);

        return implode("\n", $str);
    }

    public function retrieveHeadStruct(): Struct
    {
        if (null === $this->handler) {
            throw new \Exception('Load a file first with open()');
        }

        fseek($this->handler, 0);

        $binaries = fread($this->handler, $this->filesize);

        if (false === $binaries) {
            throw new \Exception('Failed to read file');
        }

        $elfHeader = $this->structModelFactory->make(StructModelEnum::ELF_HEADER);
        $elfHeader->read($binaries);

        return $elfHeader;
    }

    public function retrieveSectionsNames(string $binaries)
    {
        $elfSectionHeader = $this->retrieveSectionStruct();

        return substr($binaries, $elfSectionHeader->sh_offset, $elfSectionHeader->sh_size);
    }

    public function retrieveSectionStruct(?ElfSectionEnum $section = null): ?Struct
    {
        if (null === $this->handler) {
            throw new \Exception('Load a file first with open()');
        }

        fseek($this->handler, 0);

        $binaries = fread($this->handler, $this->filesize);

        if (false === $binaries) {
            throw new \Exception('Failed to read file');
        }

        $elfHeader = $this->retrieveHeadStruct($binaries);

        $shstrtab_offset = $elfHeader->e_shoff + $elfHeader->e_shstrndx * $elfHeader->e_shentsize;

        $elfSectionHeader = $this->structModelFactory->make(StructModelEnum::ELF_SECTION_HEADER);
        $elfSectionHeader->read($binaries, $shstrtab_offset);

        if (null === $section) {
            return $elfSectionHeader;
        }

        $sectionNames = $this->retrieveSectionsNames($binaries);

        $isFound = false;
        $target  = $section->value;
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

    public function extractSectionBin(ElfSectionEnum $section): ?string
    {
        if (null === $this->handler) {
            throw new \Exception('Load a file first with open()');
        }

        fseek($this->handler, 0);

        $binaries = fread($this->handler, $this->filesize);

        if (false === $binaries) {
            return null;
        }

        $elfSectionHeader = $this->retrieveSectionStruct($section);

        return $elfSectionHeader ? substr($binaries, $elfSectionHeader->sh_offset, $elfSectionHeader->sh_size) : null;
    }

    public function retrieveSectionOffset(ElfSectionEnum $section): ?int
    {
        $elfSectionHeader = $this->retrieveSectionStruct($section);

        return $elfSectionHeader ? $elfSectionHeader->sh_offset : null;
    }

    public function writeBytes(ElfSectionEnum $section, int $offset, int ...$bytes): bool
    {
        $elfSectionOffset = $this->retrieveSectionOffset($section);

        fseek($this->handler, $elfSectionOffset + $offset);

        fwrite($this->handler, pack(StructTypeEnum::UINT8->value.'*', ...$bytes));

        fflush($this->handler);

        return true;
    }
}
