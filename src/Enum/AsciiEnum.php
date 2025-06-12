<?php

declare(strict_types=1);

namespace Qdebulois\ByteSurgeon\Enum;

/** https://en.wikipedia.org/wiki/ASCII */
enum AsciiEnum: int
{
    // Control characters (0x00–0x1F)

    /** Null character */
    case NUL = 0x00;

    /** Start of Heading */
    case SOH = 0x01;

    /** Start of Text */
    case STX = 0x02;

    /** End of Text */
    case ETX = 0x03;

    /** End of Transmission */
    case EOT = 0x04;

    /** Enquiry */
    case ENQ = 0x05;

    /** Acknowledge */
    case ACK = 0x06;

    /** Bell */
    case BEL = 0x07;

    /** Backspace */
    case BS = 0x08;

    /** Horizontal Tab */
    case HT = 0x09;

    /** Line Feed */
    case LF = 0x0A;

    /** Vertical Tab */
    case VT = 0x0B;

    /** Form Feed */
    case FF = 0x0C;

    /** Carriage Return */
    case CR = 0x0D;

    /** Shift Out */
    case SO = 0x0E;

    /** Shift In */
    case SI = 0x0F;

    /** Data Link Escape */
    case DLE = 0x10;

    /** Device Control 1 */
    case DC1 = 0x11;

    /** Device Control 2 */
    case DC2 = 0x12;

    /** Device Control 3 */
    case DC3 = 0x13;

    /** Device Control 4 */
    case DC4 = 0x14;

    /** Negative Acknowledge */
    case NAK = 0x15;

    /** Synchronous Idle */
    case SYN = 0x16;

    /** End of Transmission Block */
    case ETB = 0x17;

    /** Cancel */
    case CAN = 0x18;

    /** End of Medium */
    case EM = 0x19;

    /** Substitute */
    case SUB = 0x1A;

    /** Escape */
    case ESC = 0x1B;

    /** File Separator */
    case FS = 0x1C;

    /** Group Separator */
    case GS = 0x1D;

    /** Record Separator */
    case RS = 0x1E;

    /** Unit Separator */
    case US = 0x1F;

    // Printable characters (0x20–0x7E)

    case SPACE = 0x20;

    case EXCLAMATION_MARK = 0x21;

    case DOUBLE_QUOTE = 0x22;

    case HASH = 0x23;

    case DOLLAR = 0x24;

    case PERCENT = 0x25;

    case AMPERSAND = 0x26;

    case SINGLE_QUOTE = 0x27;

    case LEFT_PARENTHESIS = 0x28;

    case RIGHT_PARENTHESIS = 0x29;

    case ASTERISK = 0x2A;

    case PLUS = 0x2B;

    case COMMA = 0x2C;

    case HYPHEN_MINUS = 0x2D;

    case PERIOD = 0x2E;

    case SLASH = 0x2F;

    case DIGIT_0 = 0x30;

    case DIGIT_1 = 0x31;

    case DIGIT_2 = 0x32;

    case DIGIT_3 = 0x33;

    case DIGIT_4 = 0x34;

    case DIGIT_5 = 0x35;

    case DIGIT_6 = 0x36;

    case DIGIT_7 = 0x37;

    case DIGIT_8 = 0x38;

    case DIGIT_9 = 0x39;

    case COLON = 0x3A;

    case SEMICOLON = 0x3B;

    case LESS_THAN = 0x3C;

    case EQUALS = 0x3D;

    case GREATER_THAN = 0x3E;

    case QUESTION_MARK = 0x3F;

    case AT_SIGN = 0x40;

    // Uppercase letters

    case A_UPPER = 0x41;

    case B_UPPER = 0x42;

    case C_UPPER = 0x43;

    case D_UPPER = 0x44;

    case E_UPPER = 0x45;

    case F_UPPER = 0x46;

    case G_UPPER = 0x47;

    case H_UPPER = 0x48;

    case I_UPPER = 0x49;

    case J_UPPER = 0x4A;

    case K_UPPER = 0x4B;

    case L_UPPER = 0x4C;

    case M_UPPER = 0x4D;

    case N_UPPER = 0x4E;

    case O_UPPER = 0x4F;

    case P_UPPER = 0x50;

    case Q_UPPER = 0x51;

    case R_UPPER = 0x52;

    case S_UPPER = 0x53;

    case T_UPPER = 0x54;

    case U_UPPER = 0x55;

    case V_UPPER = 0x56;

    case W_UPPER = 0x57;

    case X_UPPER = 0x58;

    case Y_UPPER = 0x59;

    case Z_UPPER = 0x5A;

    case LEFT_BRACKET = 0x5B;

    case BACKSLASH = 0x5C;

    case RIGHT_BRACKET = 0x5D;

    case CARET = 0x5E;

    case UNDERSCORE = 0x5F;

    case BACKTICK = 0x60;

    // Lowercase letters (uppercase with _LOWER suffix)

    case A_LOWER = 0x61;

    case B_LOWER = 0x62;

    case C_LOWER = 0x63;

    case D_LOWER = 0x64;

    case E_LOWER = 0x65;

    case F_LOWER = 0x66;

    case G_LOWER = 0x67;

    case H_LOWER = 0x68;

    case I_LOWER = 0x69;

    case J_LOWER = 0x6A;

    case K_LOWER = 0x6B;

    case L_LOWER = 0x6C;

    case M_LOWER = 0x6D;

    case N_LOWER = 0x6E;

    case O_LOWER = 0x6F;

    case P_LOWER = 0x70;

    case Q_LOWER = 0x71;

    case R_LOWER = 0x72;

    case S_LOWER = 0x73;

    case T_LOWER = 0x74;

    case U_LOWER = 0x75;

    case V_LOWER = 0x76;

    case W_LOWER = 0x77;

    case X_LOWER = 0x78;

    case Y_LOWER = 0x79;

    case Z_LOWER = 0x7A;

    case LEFT_CURLY_BRACE = 0x7B;

    case VERTICAL_BAR = 0x7C;

    case RIGHT_CURLY_BRACE = 0x7D;

    case TILDE = 0x7E;

    case DELETE = 0x7F;

    case EURO_SIGN = 0x80;

    // Extended ASCII (Windows-1252 / ISO-8859-1 supplement)

    case UNDEFINED_0x81 = 0x81;

    case UNDEFINED_0x82 = 0x82;

    case SINGLE_LOW_9_QUOTE = 0x83;

    case LATIN_SMALL_F_WITH_HOOK = 0x84;

    case DOUBLE_LOW_9_QUOTE = 0x85;

    case HORIZONTAL_ELLIPSIS = 0x86;

    case DAGGER = 0x87;

    case DOUBLE_DAGGER = 0x88;

    case MODIFIER_CIRCUMFLEX_ACCENT = 0x89;

    case PER_MILLE_SIGN = 0x8A;

    case LATIN_CAPITAL_S_CARON = 0x8B;

    case SINGLE_LEFT_POINTING_ANGLE_QUOTE = 0x8C;

    case LATIN_CAPITAL_OE = 0x8D;

    case UNDEFINED_0x8E = 0x8E;

    case UNDEFINED_0x8F = 0x8F;

    case LATIN_SMALL_Z_CARON = 0x90;

    case SIMPLE_SINGLE_RIGHT_POINTING_ANGLE_QUOTE = 0x91;

    case LATIN_SMALL_OE = 0x92;

    case LEFT_DOUBLE_QUOTATION_MARK = 0x93;

    case RIGHT_DOUBLE_QUOTATION_MARK = 0x94;

    case BULLET = 0x95;

    case EN_DASH = 0x96;

    case EM_DASH = 0x97;

    case SMALL_TILDE = 0x98;

    case TRADEMARK_SIGN = 0x99;

    case LATIN_SMALL_S_CARON = 0x9A;

    case SINGLE_RIGHT_POINTING_ANGLE_QUOTE = 0x9B;

    case LATIN_SMALL_OE_2 = 0x9C;

    case UNDEFINED_0x9D = 0x9D;

    case LATIN_SMALL_Z_CARON_2 = 0x9E;

    case LATIN_CAPITAL_Y_DIAERESIS = 0x9F;

    case NO_BREAK_SPACE = 0xA0;

    case INVERTED_EXCLAMATION_MARK = 0xA1;

    case CENT_SIGN = 0xA2;

    case POUND_SIGN = 0xA3;

    case CURRENCY_SIGN = 0xA4;

    case YEN_SIGN = 0xA5;

    case BROKEN_BAR = 0xA6;

    case SECTION_SIGN = 0xA7;

    case DIAERESIS = 0xA8;

    case COPYRIGHT_SIGN = 0xA9;

    case FEMININE_ORDINAL_INDICATOR = 0xAA;

    case LEFT_DOUBLE_ANGLE_QUOTE = 0xAB;

    case NOT_SIGN = 0xAC;

    case SOFT_HYPHEN = 0xAD;

    case REGISTERED_SIGN = 0xAE;

    case MACRON = 0xAF;

    case DEGREE_SIGN = 0xB0;

    case PLUS_MINUS_SIGN = 0xB1;

    case SUPERSCRIPT_TWO = 0xB2;

    case SUPERSCRIPT_THREE = 0xB3;

    case ACUTE_ACCENT = 0xB4;

    case MICRO_SIGN = 0xB5;

    case PILCROW_SIGN = 0xB6;

    case MIDDLE_DOT = 0xB7;

    case CEDILLA = 0xB8;

    case SUPERSCRIPT_ONE = 0xB9;

    case MASCULINE_ORDINAL_INDICATOR = 0xBA;

    case RIGHT_DOUBLE_ANGLE_QUOTE = 0xBB;

    case ONE_QUARTER = 0xBC;

    case ONE_HALF = 0xBD;

    case THREE_QUARTERS = 0xBE;

    case INVERTED_QUESTION_MARK = 0xBF;

    // Latin-1 Supplement uppercase letters with diacritics

    case A_GRAVE = 0xC0;

    case A_ACUTE = 0xC1;

    case A_CIRCUMFLEX = 0xC2;

    case A_TILDE = 0xC3;

    case A_DIAERESIS = 0xC4;

    case A_RING_ABOVE = 0xC5;

    case AE = 0xC6;

    case C_CEDILLA = 0xC7;

    case E_GRAVE = 0xC8;

    case E_ACUTE = 0xC9;

    case E_CIRCUMFLEX = 0xCA;

    case E_DIAERESIS = 0xCB;

    case I_GRAVE = 0xCC;

    case I_ACUTE = 0xCD;

    case I_CIRCUMFLEX = 0xCE;

    case I_DIAERESIS = 0xCF;

    case ETH = 0xD0;

    case N_TILDE = 0xD1;

    case O_GRAVE = 0xD2;

    case O_ACUTE = 0xD3;

    case O_CIRCUMFLEX = 0xD4;

    case O_TILDE = 0xD5;

    case O_DIAERESIS = 0xD6;

    case MULTIPLICATION_SIGN = 0xD7;

    case O_SLASH = 0xD8;

    case U_GRAVE = 0xD9;

    case U_ACUTE = 0xDA;

    case U_CIRCUMFLEX = 0xDB;

    case U_DIAERESIS = 0xDC;

    case Y_ACUTE = 0xDD;

    case THORN = 0xDE;

    case SHARP_S = 0xDF;

    // Latin-1 Supplement lowercase letters with diacritics

    case A_GRAVE_LOWER = 0xE0;

    case A_ACUTE_LOWER = 0xE1;

    case A_CIRCUMFLEX_LOWER = 0xE2;

    case A_TILDE_LOWER = 0xE3;

    case A_DIAERESIS_LOWER = 0xE4;

    case A_RING_ABOVE_LOWER = 0xE5;

    case AE_LOWER = 0xE6;

    case C_CEDILLA_LOWER = 0xE7;

    case E_GRAVE_LOWER = 0xE8;

    case E_ACUTE_LOWER = 0xE9;

    case E_CIRCUMFLEX_LOWER = 0xEA;

    case E_DIAERESIS_LOWER = 0xEB;

    case I_GRAVE_LOWER = 0xEC;

    case I_ACUTE_LOWER = 0xED;

    case I_CIRCUMFLEX_LOWER = 0xEE;

    case I_DIAERESIS_LOWER = 0xEF;

    case ETH_LOWER = 0xF0;

    case N_TILDE_LOWER = 0xF1;

    case O_GRAVE_LOWER = 0xF2;

    case O_ACUTE_LOWER = 0xF3;

    case O_CIRCUMFLEX_LOWER = 0xF4;

    case O_TILDE_LOWER = 0xF5;

    case O_DIAERESIS_LOWER = 0xF6;

    case DIVISION_SIGN = 0xF7;

    case O_SLASH_LOWER = 0xF8;

    case U_GRAVE_LOWER = 0xF9;

    case U_ACUTE_LOWER = 0xFA;

    case U_CIRCUMFLEX_LOWER = 0xFB;

    case U_DIAERESIS_LOWER = 0xFC;

    case Y_ACUTE_LOWER = 0xFD;

    case THORN_LOWER = 0xFE;

    case SHARP_S_LOWER = 0xFF;

    public function isPrintable(): bool
    {
        return $this->value >= 0x20 && $this->value <= 0x7E;
    }

    public function toChar(): string
    {
        if (!$this->isPrintable()) {
            return '·';
        }

        return chr($this->value);
    }

    public static function fromChar(string $char): ?self
    {
        if (1 !== strlen($char)) {
            throw new \Exception('Invalid char');
        }

        $ord = ord($char);

        return self::tryFrom($ord);
    }

    public static function fromByte(int $byte): ?self
    {
        return self::tryFrom($byte);
    }

    public static function map(): array
    {
        $map = [];

        foreach (self::cases() as $case) {
            $map[$case->value] = $case;
        }

        return $map;
    }
}
