<?php

declare(strict_types=1);

namespace Cog\Unicode;

final class UnicodeString
{
    /**
     * @param list<Character> $characterList
     */
    private function __construct(
        public readonly array $characterList,
    ) {
    }

    /**
     * @param list<Character> $characterList
     */
    public static function ofCharacterList(
        array $characterList,
    ): self {
        return new self(
            $characterList,
        );
    }

    public static function of(
        string $string,
    ): self {
        $charList = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);

        $characterList = [];

        foreach ($charList as $char) {
            $characterList[] = Character::of($char);
        }

        return new self(
            $characterList,
        );
    }

    public function __toString(): string
    {
        return implode('', $this->characterList);
    }

    /**
     * @return list<Character> $characterList
     */
    public function characterList(): array
    {
        return $this->characterList;
    }
}
