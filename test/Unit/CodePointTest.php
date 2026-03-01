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
        if ($htmlEntity === '&#x0;' || $htmlEntity === '&#x10FFFF;') {
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

    #[DataProvider('provideSurrogateCodePoints')]
    public function testItCannotInstantiateOfDecimalWithSurrogateCodePoint(
        int $decimal,
    ): void {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage('is a surrogate');

        CodePoint::ofDecimal($decimal);
    }

    #[DataProvider('provideSurrogateCodePoints')]
    public function testItCannotInstantiateOfHexadecimalWithSurrogateCodePoint(
        int $decimal,
    ): void {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage('is a surrogate');

        CodePoint::ofHexadecimal(sprintf('U+%04X', $decimal));
    }

    public function testItCannotInstantiateOfHtmlEntityWithInvalidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid HTML entity');

        CodePoint::ofHtmlEntity('hello');
    }

    public function testItCannotInstantiateOfHtmlEntityWithEmptyEntityName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid HTML entity');

        CodePoint::ofHtmlEntity('&;');
    }

    public function testItCannotInstantiateOfHtmlEntityWithUnknownEntity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown HTML entity');

        CodePoint::ofHtmlEntity('&foobar;');
    }

    #[DataProvider('provideSurrogateEntities')]
    public function testItCannotInstantiateOfHtmlEntityWithSurrogateCodePoint(
        string $entity,
    ): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('surrogate');

        CodePoint::ofHtmlEntity($entity);
    }

    public function testItCannotInstantiateOfXmlEntityWithInvalidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid XML entity');

        CodePoint::ofXmlEntity('hello');
    }

    public function testItCannotInstantiateOfXmlEntityWithEmptyEntityName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid XML entity');

        CodePoint::ofXmlEntity('&;');
    }

    public function testItCannotInstantiateOfXmlEntityWithUnknownEntity(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown XML entity');

        CodePoint::ofXmlEntity('&copy;');
    }

    #[DataProvider('provideSurrogateEntities')]
    public function testItCannotInstantiateOfXmlEntityWithSurrogateCodePoint(
        string $entity,
    ): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('surrogate');

        CodePoint::ofXmlEntity($entity);
    }

    public static function provideSurrogateEntities(): array
    {
        return [
            '&#xD800;' => ['&#xD800;'],
            '&#xDFFF;' => ['&#xDFFF;'],
            '&#55296;' => ['&#55296;'],
            '&#57343;' => ['&#57343;'],
        ];
    }

    public static function provideSurrogateCodePoints(): array
    {
        return [
            'U+D800 (high surrogate start)' => [0xD800],
            'U+DBFF (high surrogate end)' => [0xDBFF],
            'U+DC00 (low surrogate start)' => [0xDC00],
            'U+DFFF (low surrogate end)' => [0xDFFF],
        ];
    }

    public static function provideUnicodeMap(): array
    {
        return [
            ["\x00", 0, 'U+0000', '&#x0;', '&#x0;'],
            ['􏿿', 1114111, 'U+10FFFF', '&#x10FFFF;', '&#x10FFFF;'],
            [' ', 32, 'U+0020', '&#x20;', '&#x20;'],
            ['A', 65, 'U+0041', '&#x41;', '&#x41;'],
            [' ', 160, 'U+00A0', '&nbsp;', '&#xA0;'],
            ['ÿ', 255, 'U+00FF', '&yuml;', '&#xFF;'],
            ['Ā', 256, 'U+0100', '&Amacr;', '&#x100;'],
            ['ſ', 383, 'U+017F', '&#x17F;', '&#x17F;'],
            ['€', 8364, 'U+20AC', '&euro;', '&#x20AC;'],
            ['⚙', 9881, 'U+2699', '&#x2699;', '&#x2699;'],
            ['👨', 128104, 'U+1F468', '&#x1F468;', '&#x1F468;'],
            ['�', 65533, 'U+FFFD', '&#xFFFD;', '&#xFFFD;'],
        ];
    }
}
