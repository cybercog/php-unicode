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

        $graphemeList = $text->graphemeList();
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
        $this->assertCount(2, $text->graphemeList());
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
}
