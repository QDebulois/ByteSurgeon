<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Struct;

use Qdebulois\ByteSurgeon\Enum\StructTypeEnum;

class Struct
{
    private bool $isPopulated = false;
    private array $data;
    private array $fields       = [];
    private array $sizedStructs = [
        StructTypeEnum::CHAR_ARRAY_NULL_PADDED,
        StructTypeEnum::CHAR_ARRAY_SPACE_PADDED,
        StructTypeEnum::HEX_STRING,
        StructTypeEnum::HEX_STRING_REVERSE,
    ];

    public function __get(string $name): mixed
    {
        return $this->data[$name];
    }

    public function __set(string $name, mixed $value): void {}

    public function __toString(): string
    {
        return join(
            "\n",
            array_map(
                function (string $name, mixed $value) {
                    if (
                        StructTypeEnum::HEX_STRING === $this->fields[$name]['type']
                        || StructTypeEnum::HEX_STRING_REVERSE === $this->fields[$name]['type']
                    ) {
                        // Litle Endian
                        // $value = implode('', array_reverse(str_split($value, 2)));
                        $value = "0x{$value}";
                    }
                    return "{$name}: {$value}";
                },
                array_keys($this->data),
                $this->data,
            )
        )."\n";
    }

    public function addField(StructTypeEnum $type, string $name, ?int $size = null): self
    {
        if (isset($this->fields[$name])) {
            throw new \Exception("Field {$name} already exists");
        }

        if (in_array($type, $this->sizedStructs)) {
            if (!$size) {
                throw new \Exception("Field {$name} must have a size");
            }

            if (
                StructTypeEnum::HEX_STRING === $type
                || StructTypeEnum::HEX_STRING_REVERSE === $type
            ) {
                $size = $size * 2;
            }
        }

        $this->fields[$name] = ['type' => $type, 'size' => $size ? (string)$size : ''];

        return $this;
    }

    public function listFields(): array
    {
        return $this->fields;
    }

    public function isPopulated(): bool
    {
        return $this->isPopulated;
    }

    public function read(string $bin, int $offset = 0): int
    {
        $this->data = array_fill_keys(array_keys($this->fields), null);

        $unpackStr  = [];
        $unpackSize = 0;

        foreach ($this->fields as $name => $values) {
            $unpackStr[] = "{$values['type']->value}{$values['size']}{$name}";

            if ($values['size']) {
                $size = (int)$values['size'];

                if (
                    $values['type'] === StructTypeEnum::HEX_STRING
                    || $values['type'] === StructTypeEnum::HEX_STRING_REVERSE
                ) {
                    $size /= 2;
                }

                $unpackSize += $size;
            } else {
                $unpackSize += $values['type']->sizeOf() ?? 0;
            }
        }

        $this->data = unpack(join('/', $unpackStr), $bin, $offset);

        $this->isPopulated = true;

        return $unpackSize;
    }
}
