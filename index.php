<?php

declare(strict_types=1);

use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Surgeon\Surgeon;

require_once __DIR__ . '/vendor/autoload.php';

class Main
{
    public static function main(int $argc, array $argv): void
    {
        if (2 !== $argc) {
            echo "Usage: {$argv[0]} <file>\n\n";

            return;
        }

        // Dump its bytes — you’ll spot the ADD instruction in there (e.g., 83 C0 02 = add eax, 0x2)
        // Patch it to 83 E8 02 = sub eax, 0x2

        $filename = $argv[1];

        $surgeon = new Surgeon();

        $surgeon->open($filename);

        // $sectiontextBin = $surgeon->extractSectionBin(ElfSectionEnum::TEXT);
        // echo $surgeon->castBinToHex($sectiontextBin).PHP_EOL;

        $sectionRodataBin = $surgeon->extractSectionBin(ElfSectionEnum::RODATA);
        echo $surgeon->castBinToChars($sectionRodataBin).PHP_EOL;

        // $surgeon->writeBytes(ElfSectionEnum::RODATA, 4, ...[0x20, 0x20, 0x46, 0x55, 0x43, 0x4b]);

        // $sectionRodataBinPatched = $surgeon->extractSectionBin(ElfSectionEnum::RODATA);
        // echo $surgeon->castBinToChars($sectionRodataBinPatched).PHP_EOL;

        $surgeon->close();
    }
}

Main::main($argc, $argv);
