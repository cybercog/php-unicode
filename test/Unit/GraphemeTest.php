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
use Cog\Unicode\Grapheme;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class GraphemeTest extends TestCase
{
    #[DataProvider('provideSingleCodePointGraphemes')]
    public function testItCanInstantiateOfSingleCodePointString(
        string $graphemeString,
    ): void {
        $grapheme = Grapheme::of($graphemeString);

        $this->assertSame($graphemeString, strval($grapheme));
        $this->assertSame(1, $grapheme->codePointCount());
        $this->assertTrue($grapheme->isSingleCodePoint());
    }

    #[DataProvider('provideMultiCodePointGraphemes')]
    public function testItCanInstantiateOfMultiCodePointString(
        string $graphemeString,
        int $expectedCodePointCount,
    ): void {
        $grapheme = Grapheme::of($graphemeString);

        $this->assertSame($graphemeString, strval($grapheme));
        $this->assertSame($expectedCodePointCount, $grapheme->codePointCount());
        $this->assertFalse($grapheme->isSingleCodePoint());
    }

    public function testItCanInstantiateOfCodePointList(): void
    {
        $codePoints = [
            CodePoint::ofHexadecimal('U+0065'), // e
            CodePoint::ofHexadecimal('U+0301'), // combining acute
        ];

        $grapheme = Grapheme::ofCodePointList($codePoints);

        $this->assertSame("e\u{0301}", strval($grapheme));
        $this->assertSame(2, $grapheme->codePointCount());
        $this->assertFalse($grapheme->isSingleCodePoint());
    }

    public function testItReturnsCodePointList(): void
    {
        $grapheme = Grapheme::of('A');

        $codePointList = $grapheme->codePointList();
        $this->assertCount(1, $codePointList);
        $this->assertSame('A', strval($codePointList[0]));
    }

    public function testItReturnsCodePointListForMultiCodePointGrapheme(): void
    {
        // e + combining acute accent
        $grapheme = Grapheme::of("e\u{0301}");

        $codePointList = $grapheme->codePointList();
        $this->assertCount(2, $codePointList);
        $this->assertSame('e', strval($codePointList[0]));
        $this->assertSame("\u{0301}", strval($codePointList[1]));
    }

    public function testFamilyEmojiIsSingleGrapheme(): void
    {
        $grapheme = Grapheme::of('👨‍👩‍👧‍👦');

        $this->assertSame('👨‍👩‍👧‍👦', strval($grapheme));
        $this->assertSame(7, $grapheme->codePointCount());
        $this->assertFalse($grapheme->isSingleCodePoint());
    }

    public function testFlagEmojiIsSingleGrapheme(): void
    {
        $grapheme = Grapheme::of('🇦🇶');

        $this->assertSame('🇦🇶', strval($grapheme));
        $this->assertSame(2, $grapheme->codePointCount());
        $this->assertFalse($grapheme->isSingleCodePoint());
    }

    public function testItCannotInstantiateOfEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Grapheme::of('');
    }

    public function testItCannotInstantiateOfMultipleGraphemes(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Grapheme::of('AB');
    }

    public function testItCannotInstantiateOfCodePointListFormingMultipleGraphemes(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Grapheme::ofCodePointList([
            CodePoint::of('A'),
            CodePoint::of('B'),
        ]);
    }

    public function testItCannotInstantiateOfEmptyCodePointList(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Grapheme::ofCodePointList([]);
    }

    public static function provideSingleCodePointGraphemes(): array
    {
        return [
            'ASCII letter' => ['A'],
            'precomposed A with acute' => ['Á'],
            'Euro sign' => ['€'],
            'Emoji man' => ['👨'],
        ];
    }

    public static function provideMultiCodePointGraphemes(): array
    {
        return [
            'decomposed e + combining acute' => ["e\u{0301}", 2],
            'family emoji' => ['👨‍👩‍👧‍👦', 7],
            'flag emoji' => ['🇦🇶', 2],
        ];
    }
}
