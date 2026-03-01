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

final class UnicodeString
{
    /**
     * @param list<CodePoint> $codePointList
     */
    private function __construct(
        public readonly array $codePointList,
    ) {}

    /**
     * @param list<CodePoint> $codePointList
     */
    public static function ofCodePointList(
        array $codePointList,
    ): self {
        return new self(
            $codePointList,
        );
    }

    public static function of(
        string $string,
    ): self {
        $charList = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        $codePointList = [];

        foreach ($charList as $char) {
            $codePointList[] = CodePoint::of($char);
        }

        return new self(
            $codePointList,
        );
    }

    public function __toString(): string
    {
        return implode('', $this->codePointList);
    }
}
