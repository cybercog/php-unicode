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

namespace Test\Unit\Cog\Unicode;

use Cog\Unicode\Character;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CharacterTest extends TestCase
{
    #[DataProvider('provideUnicodeMap')]
    public function testItCanInstantiateOfCharacter(
        string $char,
        int $decimal,
        string $hexadecimal,
        string $htmlEntity,
        string $xmlEntity,
    ): void {
        $character = Character::of($char);

        $this->assertSame(
            $char,
            strval($character),
        );
        $this->assertSame(
            $decimal,
            $character->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $character->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $character->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $character->toXmlEntity(),
        );
    }

    #[DataProvider('provideUnicodeMap')]
    public function testItCanInstantiateOfDecimal(
        string $char,
        int $decimal,
        string $hexadecimal,
        string $htmlEntity,
        string $xmlEntity,
    ): void {
        $character = Character::ofDecimal($decimal);

        $this->assertSame(
            $char,
            strval($character),
        );
        $this->assertSame(
            $decimal,
            $character->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $character->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $character->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $character->toXmlEntity(),
        );
    }

    #[DataProvider('provideUnicodeMap')]
    public function testItCanInstantiateOfHexadecimal(
        string $char,
        int $decimal,
        string $hexadecimal,
        string $htmlEntity,
        string $xmlEntity,
    ): void {
        $character = Character::ofHexadecimal($hexadecimal);

        $this->assertSame(
            $char,
            strval($character),
        );
        $this->assertSame(
            $decimal,
            $character->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $character->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $character->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $character->toXmlEntity(),
        );
    }

    #[DataProvider('provideUnicodeMap')]
    public function testItCanInstantiateOfHtmlEntity(
        string $char,
        int $decimal,
        string $hexadecimal,
        string $htmlEntity,
        string $xmlEntity,
    ): void {
        $character = Character::ofHtmlEntity($htmlEntity);

        $this->assertSame(
            $char,
            strval($character),
        );
        $this->assertSame(
            $decimal,
            $character->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $character->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $character->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $character->toXmlEntity(),
        );
    }

    #[DataProvider('provideUnicodeMap')]
    public function testItCanInstantiateOfXmlEntity(
        string $char,
        int $decimal,
        string $hexadecimal,
        string $htmlEntity,
        string $xmlEntity,
    ): void {
        if ($xmlEntity === '&#x0;') {
            $this->markTestSkipped('XML does not have NULL value');
        }

        $character = Character::ofXmlEntity($xmlEntity);

        $this->assertSame(
            $char,
            strval($character),
        );
        $this->assertSame(
            $decimal,
            $character->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $character->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $character->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $character->toXmlEntity(),
        );
    }

    public function testItCannotInstantiateOfCharacterWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $char = '';

        Character::of($char);
    }

    public function testItCannotInstantiateOfCharacterWithMoreThanOneCharacter(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $char = 'AA';

        Character::of($char);
    }

    public function testItCannotInstantiateOfDecimalWithNegativeValue(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $decimal = -1;

        Character::ofDecimal($decimal);
    }

    public function testItCannotInstantiateOfHexadecimalWithTooLowValue(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $hexadecimal = 'U+FFFFFFFE'; // Min unicode hexadecimal -1

        Character::ofHexadecimal($hexadecimal);
    }

    public function testItCannotInstantiateOfHexadecimalWithTooBigValue(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $hexadecimal = 'U+110000'; // Max unicode hexadecimal +1

        Character::ofHexadecimal($hexadecimal);
    }

    public function testItCannotInstantiateOfHtmlEntityWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $htmlEntity = '';

        Character::ofHtmlEntity($htmlEntity);
    }

    public function testItCannotInstantiateOfHtmlEntityWithMoreThanOneCharacter(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $htmlEntity = '&copy;&nbsp;';

        Character::ofHtmlEntity($htmlEntity);
    }

    public function testItCannotInstantiateOfXmlEntityWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $xmlEntity = '';

        Character::ofXmlEntity($xmlEntity);
    }

    public function testItCannotInstantiateOfXmlEntityWithMoreThanOneCharacter(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $xmlEntity = '&#x2122;&#x2dc;';

        Character::ofXmlEntity($xmlEntity);
    }

    public static function provideUnicodeMap(): array
    {
        return [
            ["\x00", 0, 'U+0000', "\x00", '&#x0;'],
            ['􏿿', 1114111, 'U+10FFFF', '􏿿', '&#x10ffff;'],
            [' ', 32, 'U+0020', ' ', '&#x20;'],
            ['A', 65, 'U+0041', 'A', '&#x41;'],
            [' ', 160, 'U+00A0', '&nbsp;', '&#xa0;'],
            ['ÿ', 255, 'U+00FF', '&yuml;', '&#xff;'],
            ['Ā', 256, 'U+0100', '&Amacr;', '&#x100;'],
            ['ſ', 383, 'U+017F', 'ſ', '&#x17f;'],
            ['€', 8364, 'U+20AC', '&euro;', '&#x20ac;'],
            ['⚙', 9881, 'U+2699', '⚙', '&#x2699;'],
            ['👨', 128104, 'U+1F468', '👨', '&#x1f468;'],
            ['�', 65533, 'U+FFFD', '�', '&#xfffd;'],
        ];
    }
}
