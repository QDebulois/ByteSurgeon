<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Surgeon\Traits;

use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Surgeon\Surgeon;

/**
 * @phpstan-require-extends Surgeon
 */
trait Extractor
{
    public function extractSectionBin(ElfSectionEnum $section): ?string
    {
        /** @var Surgeon $this */
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
}
