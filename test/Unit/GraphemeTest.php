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
        $this->assertCount(1, $grapheme->codePointList);
    }

    #[DataProvider('provideMultiCodePointGraphemes')]
    public function testItCanInstantiateOfMultiCodePointString(
        string $graphemeString,
        int $expectedCodePointCount,
    ): void {
        $grapheme = Grapheme::of($graphemeString);

        $this->assertSame($graphemeString, strval($grapheme));
        $this->assertCount($expectedCodePointCount, $grapheme->codePointList);
    }

    public function testItCanInstantiateOfCodePointList(): void
    {
        $codePoints = [
            CodePoint::ofHexadecimal('U+0065'), // e
            CodePoint::ofHexadecimal('U+0301'), // combining acute
        ];

        $grapheme = Grapheme::ofCodePointList($codePoints);

        $this->assertSame("e\u{0301}", strval($grapheme));
        $this->assertCount(2, $grapheme->codePointList);
    }

    public function testItReturnsCodePointList(): void
    {
        $grapheme = Grapheme::of('A');

        $codePointList = $grapheme->codePointList;
        $this->assertCount(1, $codePointList);
        $this->assertSame('A', strval($codePointList[0]));
    }

    public function testItReturnsCodePointListForMultiCodePointGrapheme(): void
    {
        // e + combining acute accent
        $grapheme = Grapheme::of("e\u{0301}");

        $codePointList = $grapheme->codePointList;
        $this->assertCount(2, $codePointList);
        $this->assertSame('e', strval($codePointList[0]));
        $this->assertSame("\u{0301}", strval($codePointList[1]));
    }

    public function testFamilyEmojiIsSingleGrapheme(): void
    {
        $grapheme = Grapheme::of('👨‍👩‍👧‍👦');

        $this->assertSame('👨‍👩‍👧‍👦', strval($grapheme));
        $this->assertCount(7, $grapheme->codePointList);
    }

    public function testFlagEmojiIsSingleGrapheme(): void
    {
        $grapheme = Grapheme::of('🇦🇶');

        $this->assertSame('🇦🇶', strval($grapheme));
        $this->assertCount(2, $grapheme->codePointList);
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
            'Arabic letter' => ["\u{0645}"],
            'Thai letter' => ["\u{0E01}"],
            'Korean Hangul syllable' => ["\u{D55C}"],
            'Devanagari letter' => ["\u{0915}"],
        ];
    }

    public static function provideMultiCodePointGraphemes(): array
    {
        return [
            'decomposed e + combining acute' => ["e\u{0301}", 2],
            'family emoji' => ['👨‍👩‍👧‍👦', 7],
            'flag emoji' => ['🇦🇶', 2],
            'Thai with tone mark' => ["\u{0E01}\u{0E48}", 2],
            'Devanagari with vowel sign' => ["\u{0928}\u{093F}", 2],
        ];
    }
}
