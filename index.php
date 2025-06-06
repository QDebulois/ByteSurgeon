<?php

declare(strict_types=1);

use Qdebulois\ByteSurgeon\Enum\ElfSectionNameEnum;
use Qdebulois\ByteSurgeon\Enum\StructModelEnum;
use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;
use Qdebulois\ByteSurgeon\Struct\StructModelFactory;
use Qdebulois\ByteSurgeon\Surgeon\Surgeon;

require_once __DIR__ . '/vendor/autoload.php';

class Main
{
    public static function main(int $argc, array $argv): int
    {
        if (2 !== $argc) {
            echo "\nUsage: php -f {$argv[0]} <file>\n\n";

            return 1;
        }

        $handler = fopen($argv[1], 'r+b');

        $data = fread($handler, filesize($argv[1]));

        $surgeon = new Surgeon();
        $structModelFactory = new StructModelFactory();

        $elfHeader        = $structModelFactory->make(StructModelEnum::ELF_HEADER);
        $elfProgramHeader = $structModelFactory->make(StructModelEnum::ELF_PROGRAM_HEADER);
        $elfSectionHeader = $structModelFactory->make(StructModelEnum::ELF_SECTION_HEADER);

        echo "HEADER\n";
        $elfHeader->read($data);
        echo $elfHeader;

        // for ($i = 0; $i < $elfHeader->e_phnum; ++$i) {
        //     $programHeaderNbr = $i + 1;
        //     echo "PROGRAM HEADER {$programHeaderNbr}\n";

        //     $elfProgramHeader->read($data, $elfHeader->e_phoff + $i * $elfHeader->e_phentsize);
        //     echo $elfProgramHeader;
        // }

        $shstrtab_offset = $elfHeader->e_shoff + $elfHeader->e_shstrndx * $elfHeader->e_shentsize;
        $elfSectionHeader->read($data, $shstrtab_offset);
        $sectionNames = substr($data, $elfSectionHeader->sh_offset, $elfSectionHeader->sh_size);

        $target = ElfSectionNameEnum::RODATA->value;
        echo "TARGET: {$target}\n";

        for ($i = 0; $i < $elfHeader->e_shnum; ++$i) {
            $offset = $elfHeader->e_shoff + $i * $elfHeader->e_shentsize;
            $elfSectionHeader->read($data, $offset);

            $nameOffset = $elfSectionHeader->sh_name;
            $nullTerminated = substr($sectionNames, $nameOffset);
            $sectionName = strtok($nullTerminated, "\0");

            if ($target === $sectionName) {
                echo "SECTION HEADER #{$i}\n";
                echo "NAME: {$sectionName}\n";
                echo $elfSectionHeader;

                break;
            }
        }

        // Dump its bytes — you’ll spot the ADD instruction in there (e.g., 83 C0 02 = add eax, 0x2)
        // Patch it to 83 E8 02 = sub eax, 0x2

        echo "RODATA\n";
        fseek($handler, $elfSectionHeader->sh_offset);
        $data = fread($handler, $elfSectionHeader->sh_size);

        echo $surgeon->translate($data);

        echo "\n";
        echo "PATCHING\n";

        fseek($handler, $elfSectionHeader->sh_offset + 4);

        $replacement = pack(
            StructTypeEnum::UINT8->value.'*',
            ...[0x46, 0x55, 0x43, 0x4b, 0x20, 0x20],
        );
        fwrite($handler, $replacement);

        echo "DONE\n";

        fclose($handler);

        return 0;
    }
}

Main::main($argc, $argv);

