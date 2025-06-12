<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum ElfSectionEnum: string
{
    /** Executable code (machine instructions) Core program logic, usually read + exec */
    case TEXT = '.text';

    /** Initialized writable data (global/static variables) Runtime modifiable data */
    case DATA = '.data';

    /** Read-only data (constants, literal strings) Immutable data, strings */
    case RODATA = '.rodata';

    /** Uninitialized data (zero-initialized globals) No space in file, allocated at runtime */
    case BSS = '.bss';

    /** Symbol table (function/variable names, addresses) Used for linking/debugging */
    case SYMTAB = '.symtab';

    /** String table for symbol names Contains symbol names for .symtab */
    case STRTAB = '.strtab';

    /** Section header string table (section names) Contains names of all sections */
    case SHSTRTAB = '.shstrtab';

    /** .rel.text Relocation entries for .text Needed for dynamic linking/relocation */
    case RELA_TEXT = '.rela.text';

    /** Procedure Linkage Table (stubs for dynamic linking) Jump table for shared libraries */
    case PLT = '.plt';

    /** Global Offset Table (addresses for dynamic linking) Holds actual addresses resolved at runtime */
    case GOT = '.got';

    /** Initialization and finalization code Run before main() */
    case INIT = '.init';

    /** Initialization and finalization code Run after exit */
    case FINI = '.fini';

    /** Compiler version info Not used by runtime */
    case COMMENT = '.comment';

    /** Debugging info (DWARF, etc.) Present in unstripped binaries */
    case DEBUG = '.debug_*';

    /** Notes with metadata (build ID, ABI info, properties) System and build metadata */
    case NOTE = '.note.*';
}
