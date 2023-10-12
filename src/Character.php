<?php

/*
 * This file is part of PHP Unicode.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\Unicode;

final class Character
{
    private function __construct(
        public readonly int $codePoint,
    ) {
        if ($codePoint < 0x0000 || $codePoint > 0x10FFFF) {
            throw new \OutOfRangeException(
                "Character code point value `$codePoint` is out of range",
            );
        }
    }

    public static function of(
        string $string,
    ): self {
        if (mb_strlen($string) !== 1) {
            throw new \InvalidArgumentException(
                "Cannot instantiate Character of char `$string`, length is not equal to 1",
            );
        }

        return new self(
            mb_ord($string),
        );
    }

    public static function ofDecimal(
        int $decimal,
    ): self {
        return new self(
            $decimal,
        );
    }

    public static function ofHexadecimal(
        string $hexadecimal,
    ): self {
        if (preg_match('#^U\+[0-9A-Fa-f]{4,}$#', $hexadecimal) !== 1) {
            throw new \InvalidArgumentException(
                "Invalid hexadecimal format `$hexadecimal`",
            );
        }

        return new self(
            hexdec($hexadecimal),
        );
    }

    public static function ofHtmlEntity(
        string $htmlEntity,
    ): self {
        return self::of(
            html_entity_decode(
                $htmlEntity,
                ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE,
            ),
        );
    }

    public static function ofXmlEntity(
        string $xmlEntity,
    ): self {
        return self::of(
            html_entity_decode(
                $xmlEntity,
                ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE,
            ),
        );
    }

    public function __toString(): string
    {
        return mb_chr($this->codePoint);
    }

    public function toDecimal(): int
    {
        return $this->codePoint;
    }

    public function toHexadecimal(): string
    {
        return sprintf('U+%04X', $this->codePoint);
    }

    public function toHtmlEntity(): string
    {
        return htmlentities(
            strval($this),
            ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE,
        );
    }

    public function toXmlEntity(): string
    {
        return '&#x' . dechex($this->codePoint) . ';';
    }

    public function isCombining(): bool
    {
//        return $this->decimal >= 768 && $this->decimal <= 879;
        return preg_match('#\p{Mn}#u', strval($this)) === 1;
    }

//    public function resolveCodePointType(): string
//    {
//        // Graphic, Format, Control, Private-Use, Surrogate, Noncharacter, Reserved.
//    }
}
