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

use Cog\Unicode\CodePoint;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CodePointTest extends TestCase
{
    #[DataProvider('provideUnicodeMap')]
    public function testItCanInstantiateOfCodePoint(
        string $char,
        int $decimal,
        string $hexadecimal,
        string $htmlEntity,
        string $xmlEntity,
    ): void {
        $codePoint = CodePoint::of($char);

        $this->assertSame(
            $char,
            strval($codePoint),
        );
        $this->assertSame(
            $decimal,
            $codePoint->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $codePoint->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $codePoint->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $codePoint->toXmlEntity(),
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
        $codePoint = CodePoint::ofDecimal($decimal);

        $this->assertSame(
            $char,
            strval($codePoint),
        );
        $this->assertSame(
            $decimal,
            $codePoint->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $codePoint->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $codePoint->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $codePoint->toXmlEntity(),
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
        $codePoint = CodePoint::ofHexadecimal($hexadecimal);

        $this->assertSame(
            $char,
            strval($codePoint),
        );
        $this->assertSame(
            $decimal,
            $codePoint->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $codePoint->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $codePoint->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $codePoint->toXmlEntity(),
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
        if ($htmlEntity === '&#x0;' || $htmlEntity === '&#x10ffff;') {
            $this->markTestSkipped('HTML5 does not decode NULL and noncharacter references');
        }

        $codePoint = CodePoint::ofHtmlEntity($htmlEntity);

        $this->assertSame(
            $char,
            strval($codePoint),
        );
        $this->assertSame(
            $decimal,
            $codePoint->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $codePoint->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $codePoint->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $codePoint->toXmlEntity(),
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

        $codePoint = CodePoint::ofXmlEntity($xmlEntity);

        $this->assertSame(
            $char,
            strval($codePoint),
        );
        $this->assertSame(
            $decimal,
            $codePoint->toDecimal(),
        );
        $this->assertSame(
            $hexadecimal,
            $codePoint->toHexadecimal(),
        );
        $this->assertSame(
            $htmlEntity,
            $codePoint->toHtmlEntity(),
        );
        $this->assertSame(
            $xmlEntity,
            $codePoint->toXmlEntity(),
        );
    }

    public function testItCannotInstantiateOfCodePointWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $char = '';

        CodePoint::of($char);
    }

    public function testItCannotInstantiateOfCodePointWithMoreThanOneCodePoint(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $char = 'AA';

        CodePoint::of($char);
    }

    public function testItCannotInstantiateOfDecimalWithNegativeValue(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $decimal = -1;

        CodePoint::ofDecimal($decimal);
    }

    public function testItCannotInstantiateOfHexadecimalWithExcessiveValue(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $hexadecimal = 'U+FFFFFFFE';

        CodePoint::ofHexadecimal($hexadecimal);
    }

    public function testItCannotInstantiateOfHexadecimalWithTooBigValue(): void
    {
        $this->expectException(\OutOfRangeException::class);

        $hexadecimal = 'U+110000'; // Max unicode hexadecimal +1

        CodePoint::ofHexadecimal($hexadecimal);
    }

    public function testItCannotInstantiateOfHtmlEntityWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $htmlEntity = '';

        CodePoint::ofHtmlEntity($htmlEntity);
    }

    public function testItCannotInstantiateOfHtmlEntityWithMoreThanOneCodePoint(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $htmlEntity = '&copy;&nbsp;';

        CodePoint::ofHtmlEntity($htmlEntity);
    }

    public function testItCannotInstantiateOfXmlEntityWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $xmlEntity = '';

        CodePoint::ofXmlEntity($xmlEntity);
    }

    public function testItCannotInstantiateOfXmlEntityWithMoreThanOneCodePoint(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $xmlEntity = '&#x2122;&#x2dc;';

        CodePoint::ofXmlEntity($xmlEntity);
    }

    public static function provideUnicodeMap(): array
    {
        return [
            ["\x00", 0, 'U+0000', '&#x0;', '&#x0;'],
            ['􏿿', 1114111, 'U+10FFFF', '&#x10ffff;', '&#x10ffff;'],
            [' ', 32, 'U+0020', '&#x20;', '&#x20;'],
            ['A', 65, 'U+0041', '&#x41;', '&#x41;'],
            [' ', 160, 'U+00A0', '&nbsp;', '&#xa0;'],
            ['ÿ', 255, 'U+00FF', '&yuml;', '&#xff;'],
            ['Ā', 256, 'U+0100', '&Amacr;', '&#x100;'],
            ['ſ', 383, 'U+017F', '&#x17f;', '&#x17f;'],
            ['€', 8364, 'U+20AC', '&euro;', '&#x20ac;'],
            ['⚙', 9881, 'U+2699', '&#x2699;', '&#x2699;'],
            ['👨', 128104, 'U+1F468', '&#x1f468;', '&#x1f468;'],
            ['�', 65533, 'U+FFFD', '&#xfffd;', '&#xfffd;'],
        ];
    }
}
