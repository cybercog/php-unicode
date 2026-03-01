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

final class CodePoint
{
    private function __construct(
        private readonly int $value,
    ) {
        if ($value < 0x0000 || $value > 0x10FFFF) {
            throw new \OutOfRangeException(
                "Code point value `$value` is out of range",
            );
        }
    }

    public static function of(
        string $string,
    ): self {
        if (mb_strlen($string) !== 1) {
            throw new \InvalidArgumentException(
                "Cannot instantiate CodePoint of char `$string`, length is not equal to 1",
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
            hexdec(substr($hexadecimal, 2)),
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
        return mb_chr($this->value);
    }

    public function toDecimal(): int
    {
        return $this->value;
    }

    public function toHexadecimal(): string
    {
        return sprintf('U+%04X', $this->value);
    }

    public function toHtmlEntity(): string
    {
        $char = strval($this);
        $entity = htmlentities(
            $char,
            ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE,
        );

        if ($entity !== $char) {
            return $entity;
        }

        return '&#x' . dechex($this->value) . ';';
    }

    public function toXmlEntity(): string
    {
        return '&#x' . dechex($this->value) . ';';
    }

    public function isCombining(): bool
    {
        return preg_match('#\p{M}#u', strval($this)) === 1;
    }
}
