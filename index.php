<?php

declare(strict_types=1);

use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Surgeon\Surgeon;

require_once __DIR__ . '/vendor/autoload.php';

class Main
{
    public static function main(int $argc, array $argv): int
    {
        if (2 !== $argc) {
            echo "Usage: {$argv[0]} <file>\n\n";

            return 1;
        }

        // Dump its bytes — you’ll spot the ADD instruction in there (e.g., 83 C0 02 = add eax, 0x2)
        // Patch it to 83 E8 02 = sub eax, 0x2

        $surgeon = new Surgeon();

        $filepath = $argv[1];

        $handler = fopen($filepath, 'r+b');
        $binaries = fread($handler, filesize($filepath));

        $sectiontextBin = $surgeon->extractSectionBin($binaries, ElfSectionEnum::TEXT);
        echo $surgeon->castBinToHex($sectiontextBin).PHP_EOL;

        // Patch .rodata
        // $sectionRodataBin = $surgeon->extractSectionBin($binaries, ElfSectionEnum::RODATA);
        // echo $surgeon->castBinToChars($sectionRodataBin).PHP_EOL;
        // $surgeon->writeBytes($handler, $binaries, ElfSectionEnum::RODATA, 4, ...[0x20, 0x20, 0x46, 0x55, 0x43, 0x4b]);
        // rewind($handler);
        // $binaries = fread($handler, filesize($filepath));
        // $sectionRodataBinPatched = $surgeon->extractSectionBin($binaries, ElfSectionEnum::RODATA);
        // echo $surgeon->castBinToChars($sectionRodataBinPatched).PHP_EOL;

        fclose($handler);

        return 0;
    }
}

return Main::main($argc, $argv);
