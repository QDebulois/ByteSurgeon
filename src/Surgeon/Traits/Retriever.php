<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Surgeon\Traits;

use Qdebulois\ByteSurgeon\Enum\OpcodePrefixEnum;
use Qdebulois\ByteSurgeon\Dto\FoundOpcodeDto;
use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Enum\OpcodeEnum;
use Qdebulois\ByteSurgeon\Enum\OpcodeSyscallEnum;
use Qdebulois\ByteSurgeon\Enum\StructModelEnum;
use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;
use Qdebulois\ByteSurgeon\Modrm\Modrm;
use Qdebulois\ByteSurgeon\Struct\Struct;
use Qdebulois\ByteSurgeon\Surgeon\Surgeon;

/**
 * @phpstan-require-extends Surgeon
 */
trait Retriever
{
    public function retrieveHeadStruct(): Struct
    {
        /** @var Surgeon $this */
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
        /** @var Surgeon $this */
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
        /** @var Surgeon $this */
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

    public function retrieveSectionOffset(ElfSectionEnum $section): ?int
    {
        /** @var Surgeon $this */
        $elfSectionHeader = $this->retrieveSectionStruct($section);

        if (!$elfSectionHeader) {
            echo "Section {$section->value} not found".PHP_EOL;

            return null;
        }

        return $elfSectionHeader->sh_offset;
    }

    /** @return FoundOpcodeDto[] */
    public function retrieveOpcodes(): array
    {
        /** @var Surgeon $this */
        $elfSectionBinText = $this->extractSectionBin(ElfSectionEnum::TEXT);

        if (!$elfSectionBinText) {
            echo 'Section TEXT not found'.PHP_EOL;

            return [];
        }

        $binary = unpack(StructTypeEnum::UINT8->value.'*', $elfSectionBinText);

        $opcodesPrefix = OpcodePrefixEnum::map();
        $opcodes       = OpcodeEnum::cases();

        $instructions = [];
        for ($idx = 1; $idx < count($binary); ++$idx) {
            $offset = $idx - 1;
            $byte   = $binary[$idx];

            if (in_array($byte, array_flip($opcodesPrefix), true)) {
                $prefix = $opcodesPrefix[$byte];

                echo "Offset: {$offset}, Prefix: {$prefix}".PHP_EOL;

                continue;
            }

            // foreach ($opcodes as $opcode) {
            //     if ($byte !== $opcode->value) {
            //         continue;
            //     }

            //     // if (OpcodeEnum::SYSCALL_PREFIX === $opcode) {
            //     //     $opcodesSyscall = OpcodeSyscallEnum::cases();
            //     //     $opcodeSyscall  = null;
            //     //     $nextByte       = $binary[$idx + 1];

            //     //     foreach ($opcodesSyscall as $os) {
            //     //         if ($nextByte === $os->value) {
            //     //             $opcodeSyscall = $opcodeSyscall;

            //     //             break;
            //     //         }
            //     //     }

            //     //     if (!$opcodeSyscall) {
            //     //         continue;
            //     //     }

            //     //     $foundOpcode                = new FoundOpcodeDto();
            //     //     $foundOpcode->offset        = $offset;
            //     //     $foundOpcode->opcode        = $opcode;
            //     //     $foundOpcode->opcodeSyscall = $opcodeSyscall;

            //     //     $foundOpcodes[] = $foundOpcode;

            //     //     continue;
            //     // }

            //     // if (OpcodeEnum::ARITHMETIC_IMM8 === $opcode) {
            //     //     $modrmByte = $binary[$idx + 1];

            //     //     $modrm = new Modrm();
            //     //     $modrm->read($modrmByte);

            //     //     $values        = [];
            //     //     $startValue    = $idx + 2;
            //     //     $nextOpcodeIdx = $startValue + $modrm->getValueLength();
            //     //     for ($i = $startValue; $i < $nextOpcodeIdx; ++$i) {
            //     //         $values[] = $binary[$i];
            //     //     }

            //     //     $foundOpcode         = new FoundOpcodeDto();
            //     //     $foundOpcode->offset = $offset;
            //     //     $foundOpcode->opcode = $opcode;
            //     //     $foundOpcode->modrm  = $modrm;
            //     //     $foundOpcode->values = array_reverse($values); // Penser au retour en Big endian

            //     //     $foundOpcodes[] = $foundOpcode;

            //     //     $idx = $nextOpcodeIdx;

            //     //     continue;
            //     // }

            //     // $foundOpcode         = new FoundOpcodeDto();
            //     // $foundOpcode->offset = $offset;
            //     // $foundOpcode->opcode = $opcode;

            //     // $foundOpcodes[] = $foundOpcode;
            // }
        }

        return $instructions;
    }
}
