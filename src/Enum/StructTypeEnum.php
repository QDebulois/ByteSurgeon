<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

enum StructTypeEnum: string
{
    // STRINGS

    /** char[] - ASCII string, padded with NULLs */
    case CHAR_ARRAY_NULL_PADDED = 'a';

    /** char[] - ASCII string, padded with spaces */
    case CHAR_ARRAY_SPACE_PADDED = 'A';

    /** char[] - Hex string, low nibble first (half-byte per char) */
    case HEX_STRING_REVERSE = 'h';

    /** char[] - Hex string, high nibble first (half-byte per char) */
    case HEX_STRING = 'H';

    // 8-BIT INTEGERS

    /** int8_t - 8-bit signed integer */
    case INT8 = 'c';

    /** uint8_t - 8-bit unsigned integer */
    case UINT8 = 'C';

    // 16-BIT INTEGERS

    /** int16_t - 16-bit signed integer (machine endian) */
    case INT16 = 's';

    /** uint16_t - 16-bit unsigned integer (machine endian) */
    case UINT16 = 'S';

    /** uint16_t - 16-bit unsigned integer (big-endian) */
    case UINT16_BE = 'n';

    /** uint16_t - 16-bit unsigned integer (little-endian) */
    case UINT16_LE = 'v';

    // 32-BIT INTEGERS

    /** int32_t - 32-bit signed integer (machine endian) */
    case INT32 = 'i';

    /** uint32_t - 32-bit unsigned integer (machine endian) */
    case UINT32 = 'I';

    /** int32_t - long signed integer (machine endian) */
    case INT32_LONG = 'l';

    /** uint32_t - long unsigned integer (machine endian) */
    case UINT32_LONG = 'L';

    /** uint32_t - 32-bit unsigned integer (big-endian) */
    case UINT32_BE = 'N';

    /** uint32_t - 32-bit unsigned integer (little-endian) */
    case UINT32_LE = 'V';

    // 64-BIT INTEGERS

    /** int64_t - 64-bit signed integer (machine endian) */
    case INT64 = 'q';

    /** uint64_t - 64-bit unsigned integer (machine endian) */
    case UINT64 = 'Q';

    // FLOAT

    /** float - 32-bit float (machine endian) */
    case FLOAT32 = 'f';

    /** double - 64-bit float (machine endian) */
    case FLOAT64 = 'd';

    // SPECIAL

    /** pad byte - skip 1 byte */
    case PADDING = 'x';

    /** rewind byte - move pointer back 1 byte */
    case REWIND = 'X';

    /** set absolute offset (seek to byte position) */
    case ABSOLUTE_OFFSET = '@';

    public function sizeOf(): ?int
    {
        return match ($this) {
            self::CHAR_ARRAY_NULL_PADDED, self::CHAR_ARRAY_SPACE_PADDED => 1,
            self::HEX_STRING, self::HEX_STRING_REVERSE => 1,
            self::INT8, self::UINT8 => 1,
            self::INT16, self::UINT16, self::UINT16_BE, self::UINT16_LE => 2,
            self::INT32, self::UINT32, self::INT32_LONG, self::UINT32_LONG, self::UINT32_BE, self::UINT32_LE => 4,
            self::INT64, self::UINT64 => 8,
            self::FLOAT32 => 4,
            self::FLOAT64 => 8,
            self::PADDING, self::REWIND => 1,
            self::ABSOLUTE_OFFSET => null,
        };
    }
}
