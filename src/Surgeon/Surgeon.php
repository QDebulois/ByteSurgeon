<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Surgeon;

use Qdebulois\ByteSurgeon\Enum\ElfSectionEnum;
use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;
use Qdebulois\ByteSurgeon\Struct\StructModelFactory;
use Qdebulois\ByteSurgeon\Surgeon\Traits\Caster;
use Qdebulois\ByteSurgeon\Surgeon\Traits\Extractor;
use Qdebulois\ByteSurgeon\Surgeon\Traits\Retriever;

class Surgeon
{
    use Caster;
    use Retriever;
    use Extractor;

    private int $maxColPerLine = 70;
    private mixed $handler     = null;
    private ?string $filename  = null;
    private ?int $filesize     = null;
    private StructModelFactory $structModelFactory;

    public function __construct()
    {
        $this->structModelFactory = new StructModelFactory();
    }

    public function setMaxColPerLine(int $maxColPerLine): void
    {
        $this->maxColPerLine = $maxColPerLine;
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

    public function close(): void
    {
        fclose($this->handler);
    }
}
