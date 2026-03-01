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

final class Grapheme
{
    /**
     * @param list<CodePoint> $codePointList
     */
    private function __construct(
        public readonly array $codePointList,
    ) {}

    public static function of(
        string $string,
    ): self {
        if (!extension_loaded('intl')) {
            throw new \RuntimeException(
                'The "intl" extension is required to use Grapheme',
            );
        }

        if (grapheme_strlen($string) !== 1) {
            throw new \InvalidArgumentException(
                "Cannot instantiate Grapheme of string `$string`, grapheme length is not equal to 1",
            );
        }

        $codePointStringList = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        $codePointList = [];

        foreach ($codePointStringList as $codePointString) {
            $codePointList[] = CodePoint::of($codePointString);
        }

        return new self(
            $codePointList,
        );
    }

    /**
     * @param list<CodePoint> $codePointList
     */
    public static function ofCodePointList(
        array $codePointList,
    ): self {
        if (!extension_loaded('intl')) {
            throw new \RuntimeException(
                'The "intl" extension is required to use Grapheme',
            );
        }

        if (count($codePointList) === 0) {
            throw new \InvalidArgumentException(
                'Cannot instantiate Grapheme from empty code point list',
            );
        }

        $string = implode('', array_map('strval', $codePointList));

        if (grapheme_strlen($string) !== 1) {
            throw new \InvalidArgumentException(
                "Cannot instantiate Grapheme: code points do not form a single grapheme",
            );
        }

        return new self(
            $codePointList,
        );
    }

    public function __toString(): string
    {
        return implode('', $this->codePointList);
    }

    /**
     * @return list<CodePoint>
     */
    public function codePointList(): array
    {
        return $this->codePointList;
    }

    public function codePointCount(): int
    {
        return count($this->codePointList);
    }

    public function isSingleCodePoint(): bool
    {
        return count($this->codePointList) === 1;
    }
}
