<?php

declare(strict_types=1);

use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Enum\OpcodeEnum;
use Qdebulois\ByteSurgeon\Modrm\Modrm;
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


        $filename = $argv[1];

        $surgeon = new Surgeon();

        $surgeon->open($filename);

        $sectiontextBin = $surgeon->extractSectionBin(ElfSectionEnum::TEXT);
        if ($sectiontextBin) {
            echo $surgeon->castBinToHex($sectiontextBin).PHP_EOL;
        }

        // echo $surgeon->retrieveSectionsNames().PHP_EOL;

        // $sectionRodataBin = $surgeon->extractSectionBin(ElfSectionEnum::RODATA);
        // if ($sectionRodataBin) {
        //     echo $surgeon->castBinToChars($sectionRodataBin).PHP_EOL;
        // }

        // $surgeon->writeBytes(ElfSectionEnum::RODATA, 4, ...[0x20, 0x20, 0x46, 0x55, 0x43, 0x4b]);
        // $sectionRodataBinPatched = $surgeon->extractSectionBin(ElfSectionEnum::RODATA);
        // echo $surgeon->castBinToChars($sectionRodataBinPatched).PHP_EOL;

        // Dump its bytes — you’ll spot the ADD instruction in there
        // (e.g., 83 C0 02 = add eax, 0x2)
        // Patch it to 83 E8 02 = sub eax, 0x2

        // [Préfixes] [VEX|XOP] [Opcode] [ModR/M] [SIB] [Déplacement] [Données immédiates]

        $foundInstructions = $surgeon->retrieveOpcodes();
        $countFoundOpcodes = count($foundInstructions);

        // echo "{$countFoundOpcodes} OPCODES FOUND\n";

        // $txtOperations = [];
        // foreach ($foundInstructions as $foundOpcode) {
        //     $textOperation = '';
        //     $textOperation .= sprintf('%04X : %s ', $foundOpcode->offset, $foundOpcode->opcode->name);

        //     if (null !== $foundOpcode->modrm) {
        //         $textOperation .= "{$foundOpcode->modrm->getReg()->name} ";
        //         $textOperation .= "{$foundOpcode->modrm->getMod()->name} ";
        //         $textOperation .= "{$foundOpcode->modrm->getRm()->name}";

        //         if ($foundOpcode->modrm->hasSib()) {
        //             $textOperation .= ' SIB';
        //         }
        //     }

        //     if (count($foundOpcode->values)) {
        //         $hexValue = implode('', array_map(fn ($v) => sprintf('%02X', $v), $foundOpcode->values));
        //         $textOperation .= sprintf(",0x%s", $hexValue);
        //     }

        //     $txtOperations[] = $textOperation;
        // }

        // echo implode("\n", $txtOperations).PHP_EOL;

        // print_r($surgeon->retrieveOpcodes());

        // echo "ModRM".PHP_EOL;

        // $sectionTextBin = $surgeon->extractSectionBin(ElfSectionEnum::TEXT);
        // if ($sectionTextBin) {
        //     echo $surgeon->castBinToHex($sectionTextBin).PHP_EOL;
        // }

        // $operation = [0x83, 0xC0, 0x02];

        // $modrm = new Modrm();
        // $modrm->read($operation[1]);

        // echo "Mod: {$modrm->getMod()->name}".PHP_EOL;
        // echo "Reg: {$modrm->getReg()->name}".PHP_EOL;
        // echo "Rm: {$modrm->getRm()->name}".PHP_EOL;

        $surgeon->close();
    }
}

Main::main($argc, $argv);
