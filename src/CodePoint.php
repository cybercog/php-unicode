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

final class CodePoint implements \Stringable
{
    private function __construct(
        private readonly int $value,
    ) {
        if ($value < 0x0000 || $value > 0x10FFFF) {
            throw new \OutOfRangeException(
                "Code point value `$value` is out of range",
            );
        }

        if ($value >= 0xD800 && $value <= 0xDFFF) {
            throw new \OutOfRangeException(
                sprintf('Code point U+%04X is a surrogate and not a valid Unicode scalar value', $value),
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
            (int) hexdec(substr($hexadecimal, 2)),
        );
    }

    public static function ofHtmlEntity(
        string $htmlEntity,
    ): self {
        if (preg_match('/^&([a-zA-Z][a-zA-Z0-9]*|#[0-9]+|#x[0-9a-fA-F]+);$/', $htmlEntity) !== 1) {
            throw new \InvalidArgumentException(
                "Invalid HTML entity: $htmlEntity",
            );
        }

        self::rejectSurrogateNumericEntity($htmlEntity);

        $decoded = html_entity_decode(
            $htmlEntity,
            ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE,
        );

        if ($decoded === $htmlEntity) {
            throw new \InvalidArgumentException(
                "Unknown HTML entity: $htmlEntity",
            );
        }

        return self::of($decoded);
    }

    public static function ofXmlEntity(
        string $xmlEntity,
    ): self {
        if (preg_match('/^&([a-zA-Z][a-zA-Z0-9]*|#[0-9]+|#x[0-9a-fA-F]+);$/', $xmlEntity) !== 1) {
            throw new \InvalidArgumentException(
                "Invalid XML entity: $xmlEntity",
            );
        }

        self::rejectSurrogateNumericEntity($xmlEntity);

        $decoded = html_entity_decode(
            $xmlEntity,
            ENT_XML1 | ENT_QUOTES | ENT_SUBSTITUTE,
        );

        if ($decoded === $xmlEntity) {
            throw new \InvalidArgumentException(
                "Unknown XML entity: $xmlEntity",
            );
        }

        return self::of($decoded);
    }

    private static function rejectSurrogateNumericEntity(
        string $entity,
    ): void {
        if (preg_match('/^&#x([0-9a-fA-F]+);$/', $entity, $matches) === 1) {
            $value = (int) hexdec($matches[1]);
        } elseif (preg_match('/^&#([0-9]+);$/', $entity, $matches) === 1) {
            $value = (int) $matches[1];
        } else {
            return;
        }

        if ($value >= 0xD800 && $value <= 0xDFFF) {
            throw new \InvalidArgumentException(
                sprintf('%s references surrogate code point U+%04X', $entity, $value),
            );
        }
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

        return '&#x' . strtoupper(dechex($this->value)) . ';';
    }

    public function toXmlEntity(): string
    {
        return '&#x' . strtoupper(dechex($this->value)) . ';';
    }

    public function isCombining(): bool
    {
        return preg_match('#\p{M}#u', strval($this)) === 1;
    }
}
