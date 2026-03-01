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

use Cog\Unicode\Grapheme;
use Cog\Unicode\GraphemeString;
use PHPUnit\Framework\TestCase;

final class GraphemeStringTest extends TestCase
{
    public function testBasicChars(): void
    {
        $string = 'Mark';

        $text = GraphemeString::of($string);

        $graphemeList = $text->graphemeList;
        $this->assertCount(4, $graphemeList);
        $this->assertSame('M', strval($graphemeList[0]));
        $this->assertSame('a', strval($graphemeList[1]));
        $this->assertSame('r', strval($graphemeList[2]));
        $this->assertSame('k', strval($graphemeList[3]));
    }

    public function testComposedChars(): void
    {
        $string = 'ÁÆÖé';

        $text = GraphemeString::of($string);

        $graphemeList = $text->graphemeList;
        $this->assertCount(4, $graphemeList);
        $this->assertSame('Á', strval($graphemeList[0]));
        $this->assertSame('Æ', strval($graphemeList[1]));
        $this->assertSame('Ö', strval($graphemeList[2]));
        $this->assertSame('é', strval($graphemeList[3]));
    }

    public function testCombiningChars(): void
    {
        $string = "A\u{0301}\u{0300}b\u{0301}c\u{0301}d\u{0301}";

        $text = GraphemeString::of($string);

        $graphemeList = $text->graphemeList;
        $this->assertCount(4, $graphemeList);
        $this->assertSame("A\u{0301}\u{0300}", strval($graphemeList[0]));
        $this->assertSame("b\u{0301}", strval($graphemeList[1]));
        $this->assertSame("c\u{0301}", strval($graphemeList[2]));
        $this->assertSame("d\u{0301}", strval($graphemeList[3]));
    }

    public function testEmojiWithZwj(): void
    {
        $string = '👨‍👩‍👧‍👦';

        $text = GraphemeString::of($string);

        $graphemeList = $text->graphemeList;
        $this->assertCount(1, $graphemeList);
        $this->assertSame('👨‍👩‍👧‍👦', strval($graphemeList[0]));
    }

    public function testFlagEmoji(): void
    {
        $string = '🇦🇶';

        $text = GraphemeString::of($string);

        $graphemeList = $text->graphemeList;
        $this->assertCount(1, $graphemeList);
        $this->assertSame('🇦🇶', strval($graphemeList[0]));
    }

    public function testMixedContent(): void
    {
        // ASCII + precomposed + decomposed + emoji
        $string = "Ae\u{0301}👨‍👩‍👧‍👦";

        $text = GraphemeString::of($string);

        $graphemeList = $text->graphemeList;
        $this->assertCount(3, $graphemeList);
        $this->assertSame('A', strval($graphemeList[0]));
        $this->assertSame("e\u{0301}", strval($graphemeList[1]));
        $this->assertSame('👨‍👩‍👧‍👦', strval($graphemeList[2]));
    }

    public function testItCanCreateFromGraphemeList(): void
    {
        $graphemeList = [
            Grapheme::of('H'),
            Grapheme::of('i'),
        ];

        $text = GraphemeString::ofGraphemeList($graphemeList);

        $this->assertSame('Hi', strval($text));
        $this->assertCount(2, $text->graphemeList);
    }

    public function testToStringRoundTrip(): void
    {
        $string = '👨‍👩‍👧‍👦';

        $text = GraphemeString::of($string);

        $this->assertSame($string, strval($text));
    }

    public function testEmptyString(): void
    {
        $text = GraphemeString::of('');

        $this->assertCount(0, $text->graphemeList);
        $this->assertSame('', strval($text));
    }

    public function testArabicString(): void
    {
        // مرحبا — 5 Arabic letters, each a single grapheme
        $string = "\u{0645}\u{0631}\u{062D}\u{0628}\u{0627}";

        $text = GraphemeString::of($string);

        $this->assertCount(5, $text->graphemeList);
        $this->assertSame($string, strval($text));
    }

    public function testThaiWithCombiningMarks(): void
    {
        // ก่อ — ko kai + mai ek + o ang = 2 graphemes (ก่ and อ)
        $string = "\u{0E01}\u{0E48}\u{0E2D}";

        $text = GraphemeString::of($string);

        $this->assertCount(2, $text->graphemeList);
        $this->assertSame("\u{0E01}\u{0E48}", strval($text->graphemeList[0]));
        $this->assertSame("\u{0E2D}", strval($text->graphemeList[1]));
    }

    public function testKoreanHangulString(): void
    {
        // 한글 — 2 Hangul syllables, each a single grapheme
        $string = "\u{D55C}\u{AE00}";

        $text = GraphemeString::of($string);

        $this->assertCount(2, $text->graphemeList);
        $this->assertSame("\u{D55C}", strval($text->graphemeList[0]));
        $this->assertSame("\u{AE00}", strval($text->graphemeList[1]));
    }

    public function testDevanagariWithCombiningMarks(): void
    {
        // नि — na + i vowel sign = 1 grapheme with 2 code points
        $string = "\u{0928}\u{093F}";

        $text = GraphemeString::of($string);

        $this->assertCount(1, $text->graphemeList);
        $this->assertSame($string, strval($text->graphemeList[0]));
    }
}
