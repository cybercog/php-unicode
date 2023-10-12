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

        $characterList = $text->characterList;
        $this->assertSame('M', strval($characterList[0]));
        $this->assertSame('a', strval($characterList[1]));
        $this->assertSame('r', strval($characterList[2]));
        $this->assertSame('k', strval($characterList[3]));
    }

    public function testComposedChars(): void
    {
        $string = 'ÁÆÖé';

        $text = UnicodeString::of($string);

        $characterList = $text->characterList();
        $this->assertSame('Á', strval($characterList[0]));
        $this->assertSame('Æ', strval($characterList[1]));
        $this->assertSame('Ö', strval($characterList[2]));
        $this->assertSame('é', strval($characterList[3]));
    }

    public function testCombiningChars(): void
    {
        $this->markTestIncomplete('Implement combining chars');

        $string = 'Á̀b́ćd́';

        $text = UnicodeString::of($string);

        $characterList = $text->characterList;
        $this->assertSame('Á̀', strval($characterList[0]));
        $this->assertSame('b́', strval($characterList[1]));
        $this->assertSame('ć', strval($characterList[2]));
        $this->assertSame('d́', strval($characterList[3]));
    }

    public function testEmojiCombiningChars(): void
    {
        $this->markTestIncomplete('Implement combining chars');

        $string = '👨‍👩‍👧‍👦';

        $text = UnicodeString::of($string);

        $characterList = $text->characterList;
        $this->assertSame('👨', strval($characterList[0]));
        $this->assertSame('👩', strval($characterList[1]));
        $this->assertSame('👧', strval($characterList[2]));
        $this->assertSame('👦', strval($characterList[3]));
    }
}
