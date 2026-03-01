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

use Cog\Unicode\UnicodeString;
use PHPUnit\Framework\TestCase;

final class UnicodeStringTest extends TestCase
{
    public function testBasicChars(): void
    {
        $string = 'Mark';

        $text = UnicodeString::of($string);

        $codePointList = $text->codePointList;
        $this->assertSame('M', strval($codePointList[0]));
        $this->assertSame('a', strval($codePointList[1]));
        $this->assertSame('r', strval($codePointList[2]));
        $this->assertSame('k', strval($codePointList[3]));
    }

    public function testComposedChars(): void
    {
        $string = 'ÁÆÖé';

        $text = UnicodeString::of($string);

        $codePointList = $text->codePointList();
        $this->assertSame('Á', strval($codePointList[0]));
        $this->assertSame('Æ', strval($codePointList[1]));
        $this->assertSame('Ö', strval($codePointList[2]));
        $this->assertSame('é', strval($codePointList[3]));
    }

    public function testCombiningCharsAsSeparateCodePoints(): void
    {
        // A + combining acute + combining grave = 3 code points
        $string = "A\u{0301}\u{0300}";

        $text = UnicodeString::of($string);

        $codePointList = $text->codePointList;
        $this->assertCount(3, $codePointList);
        $this->assertSame('A', strval($codePointList[0]));
        $this->assertSame("\u{0301}", strval($codePointList[1]));
        $this->assertSame("\u{0300}", strval($codePointList[2]));
    }

    public function testEmojiZwjSequenceAsSeparateCodePoints(): void
    {
        // 👨‍👩‍👧‍👦 = 👨 ZWJ 👩 ZWJ 👧 ZWJ 👦 = 7 code points
        $string = '👨‍👩‍👧‍👦';

        $text = UnicodeString::of($string);

        $codePointList = $text->codePointList;
        $this->assertCount(7, $codePointList);
        $this->assertSame('👨', strval($codePointList[0]));
        $this->assertSame("\u{200D}", strval($codePointList[1]));
        $this->assertSame('👩', strval($codePointList[2]));
        $this->assertSame("\u{200D}", strval($codePointList[3]));
        $this->assertSame('👧', strval($codePointList[4]));
        $this->assertSame("\u{200D}", strval($codePointList[5]));
        $this->assertSame('👦', strval($codePointList[6]));
    }
}
