<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Struct;

use Qdebulois\ByteSurgeon\Enum\StructModelEnum;
use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;
use Qdebulois\ByteSurgeon\Struct\Struct;

class StructModelFactory
{
    public function make(StructModelEnum $structModel): Struct
    {
        return match ($structModel) {
            StructModelEnum::ELF_HEADER         => $this->makeElfHeader(),
            StructModelEnum::ELF_PROGRAM_HEADER => $this->makeElfProgramHeader(),
            StructModelEnum::ELF_SECTION_HEADER => $this->makeElfSectionHeader(),
        };
    }

    private function makeElfHeader(): Struct
    {
        return (new Struct())
            ->setModel(StructModelEnum::ELF_HEADER)

            // Magic identifier and basic info (16 bytes)
            ->addField(StructTypeEnum::HEX_STRING, 'e_ident', 16)

            // ELF file type (e.g., executable, object) - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_type')

            // Target architecture (e.g., x86-64) - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_machine')

            // ELF format version (usually 1) - 4 bytes
            ->addField(StructTypeEnum::UINT32, 'e_version')

            // Entry point address (start of execution) - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'e_entry')

            // Offset to the program header table - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'e_phoff')

            // Offset to the section header table - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'e_shoff')

            // Architecture-specific flags - 4 bytes
            ->addField(StructTypeEnum::UINT32, 'e_flags')

            // Size of this ELF header - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_phentsize')

            // Size of a program header entry - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_ehsize')

            // Number of entries in the program header table - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_phnum')

            // Size of a section header entry - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_shentsize')

            // Number of entries in the section header table - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_shnum')

            // Index of the section containing section names - 2 bytes
            ->addField(StructTypeEnum::UINT16, 'e_shstrndx')
        ;
    }

    private function makeElfProgramHeader(): Struct
    {
        return (new Struct())
            ->setModel(StructModelEnum::ELF_PROGRAM_HEADER)

            // Segment type - 4 bytes
            ->addField(StructTypeEnum::HEX_STRING, 'p_type', 4)

            // Segment flags - 4 bytes
            ->addField(StructTypeEnum::UINT32, 'p_flags')

            // Offset of the segment in the file - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'p_offset')

            // Virtual address of the segment - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'p_vaddr')

            // Physical address of the segment - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'p_paddr')

            // Size of the segment in the file - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'p_filesz')

            // Size of the segment in memory - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'p_memsz')

            // Segment alignment - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'p_align')
        ;
    }

    private function makeElfSectionHeader(): Struct
    {
        return (new Struct())
            ->setModel(StructModelEnum::ELF_SECTION_HEADER)

            // Offset in the section header string table - 4 bytes
            ->addField(StructTypeEnum::UINT32, 'sh_name')

            // Section type (e.g., SHT_PROGBITS, SHT_SYMTAB) - 4 bytes
            ->addField(StructTypeEnum::UINT32, 'sh_type')

            // Section attributes/flags (e.g., SHF_EXECINSTR) - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'sh_flags')

            // Virtual address in memory (if loaded) - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'sh_addr')

            // Offset of the section in the file - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'sh_offset')

            // Size of the section in the file - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'sh_size')

            // Section header index link (e.g., related section) - 4 bytes
            ->addField(StructTypeEnum::UINT32, 'sh_link')

            // Extra info depending on section type - 4 bytes
            ->addField(StructTypeEnum::UINT32, 'sh_info')

            // Section alignment (must be power of two) - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'sh_addralign')

            // Entry size if section contains a table (like symtab) - 8 bytes
            ->addField(StructTypeEnum::UINT64, 'sh_entsize')
        ;
    }
}
