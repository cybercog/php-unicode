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

final class GraphemeString
{
    /**
     * @param list<Grapheme> $graphemeList
     */
    private function __construct(
        public readonly array $graphemeList,
    ) {}

    /**
     * @param list<Grapheme> $graphemeList
     */
    public static function ofGraphemeList(
        array $graphemeList,
    ): self {
        return new self(
            $graphemeList,
        );
    }

    public static function of(
        string $string,
    ): self {
        if (!extension_loaded('intl')) {
            throw new \RuntimeException(
                'The "intl" extension is required to use GraphemeString',
            );
        }

        $length = grapheme_strlen($string);

        if ($length === false) {
            throw new \InvalidArgumentException(
                'Cannot split string into graphemes: invalid UTF-8 input',
            );
        }

        $graphemeList = [];

        for ($i = 0; $i < $length; $i++) {
            $graphemeList[] = Grapheme::of(grapheme_substr($string, $i, 1));
        }

        return new self(
            $graphemeList,
        );
    }

    public function __toString(): string
    {
        return implode('', $this->graphemeList);
    }

    /**
     * @return list<Grapheme>
     */
    public function graphemeList(): array
    {
        return $this->graphemeList;
    }
}
