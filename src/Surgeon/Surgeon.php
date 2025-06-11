<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Surgeon;

use Qdebulois\ByteSurgeon\Enum\AsciiEnum;
use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Enum\OpcodeEnum;
use Qdebulois\ByteSurgeon\Enum\StructModelEnum;
use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;
use Qdebulois\ByteSurgeon\Modrm\Modrm;
use Qdebulois\ByteSurgeon\Struct\Struct;
use Qdebulois\ByteSurgeon\Struct\StructModelFactory;

class Surgeon
{
    private int $maxPerLine   = 70;
    private mixed $handler    = null;
    private ?string $filename = null;
    private ?int $filesize    = null;
    private StructModelFactory $structModelFactory;

    public function __construct()
    {
        $this->structModelFactory = new StructModelFactory();
    }

    public function setMaxPerLine(int $maxPerLine): void
    {
        $this->maxPerLine = $maxPerLine;
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
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $delimiter = ' ';
        $output    = [];
        $ints      = [];
        foreach ($binary as $byte) {
            $ints[] = $byte;

            if (count($ints) > $this->maxPerLine) {
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
            $asciis[] = AsciiEnum::fromInt($byte);

            if (count($asciis) > $this->maxPerLine) {
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
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $delimiter = ' ';
        $output    = [];
        $hexs      = [];
        foreach ($binary as $byte) {
            $hexs[] = $this->castBinToHex($byte);

            if (count($hexs) > $this->maxPerLine) {
                $output[] = implode($delimiter, $hexs);

                $hexs = [];
            }
        }

        $output[] = implode($delimiter, $hexs);

        return implode("\n", $output);
    }

    public function castBinToStrbin(string $binary): string
    {
        $binary = unpack(StructTypeEnum::UINT8->value.'*', $binary);

        $delimiter = ' ';
        $output    = [];
        $strbins   = [];
        foreach ($binary as $byte) {
            $strbins[] = $this->castByteToStrbin($byte);

            if (count($strbins) > $this->maxPerLine) {
                $output[] = implode($delimiter, $strbins);

                $strbins = [];
            }
        }

        $output[] = implode($delimiter, $strbins);

        return implode("\n", $output);
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

    public function retrieveSectionsNames()
    {
        if (null === $this->handler) {
            throw new \Exception('Load a file first with open()');
        }

        fseek($this->handler, 0);

        $binaries = fread($this->handler, $this->filesize);

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

        $elfHeader = $this->retrieveHeadStruct();

        $shstrtab_offset = $elfHeader->e_shoff + $elfHeader->e_shstrndx * $elfHeader->e_shentsize;

        $elfSectionHeader = $this->structModelFactory->make(StructModelEnum::ELF_SECTION_HEADER);
        $elfSectionHeader->read($binaries, $shstrtab_offset);

        if (null === $section) {
            return $elfSectionHeader;
        }

        $sectionNames = $this->retrieveSectionsNames();

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

    public function retrieveOpcodes(): ?string
    {
        $elfSectionBinText = $this->extractSectionBin(ElfSectionEnum::TEXT);

        if (!$elfSectionBinText) {
            echo 'Section TEXT not found'.PHP_EOL;

            return null;
        }

        $binary = unpack(StructTypeEnum::UINT8->value.'*', $elfSectionBinText);


        $opcodes = OpcodeEnum::cases();

        $operations = [];
        for ($idx = 1; $idx < count($binary); ++$idx) {
            $offset = $idx - 1;
            $byte   = $binary[$idx];


            foreach ($opcodes as $opcode) {
                if ($byte !== $opcode->value) {
                    continue;
                }

                if (
                    in_array(
                        $opcode->name,
                        [
                            OpcodeEnum::SYSCALL_PREFIX->name,
                            OpcodeEnum::JMP_REL32->name,
                            OpcodeEnum::MOV_REG_IMM8->name,
                            // OpcodeEnum::MOV_REG_IMM32->name,
                            OpcodeEnum::ADD_REG_MEM->name,
                            OpcodeEnum::ADD_REG_MEM_TO_REG->name,
                        ], // On verra plus tard
                    )
                ) {
                    continue;
                }

                $operation = '';

                $modrm = new Modrm();
                $modrm->read($binary[$idx + 1]);

                $operation .= sprintf("%04X : %s ",$offset, $opcode->name);

                $operation .= "{$modrm->getReg()->name} ";
                $operation .= "{$modrm->getMod()->name} ";
                $operation .= "{$modrm->getRm()->name}";

                $value = $binary[$idx + 2];

                $operation .= sprintf(",%02X", $value);

                $operations[] = $operation;

                $idx += 2;
            }
        }

        echo implode("\n", $operations).PHP_EOL;

        return null;
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

        if (!$elfSectionHeader) {
            echo "Section {$section->value} not found".PHP_EOL;

            return null;
        }

        return substr($binaries, $elfSectionHeader->sh_offset, $elfSectionHeader->sh_size);
    }

    public function retrieveSectionOffset(ElfSectionEnum $section): ?int
    {
        $elfSectionHeader = $this->retrieveSectionStruct($section);

        if (!$elfSectionHeader) {
            echo "Section {$section->value} not found".PHP_EOL;

            return null;
        }

        return $elfSectionHeader->sh_offset;
    }

    public function writeBytes(ElfSectionEnum $section, int $offset, int ...$bytes): bool
    {
        $elfSectionOffset = $this->retrieveSectionOffset($section);

        if (null === $elfSectionOffset) {
            echo "Section {$section->value} not found".PHP_EOL;

            return false;
        }

        fseek($this->handler, $elfSectionOffset + $offset);

        fwrite($this->handler, pack(StructTypeEnum::UINT8->value.'*', ...$bytes));

        fflush($this->handler);

        return true;
    }
}
