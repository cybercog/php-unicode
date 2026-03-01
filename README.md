# PHP Unicode

<p align="center">
<a href="https://github.com/cybercog/php-unicode/releases"><img src="https://img.shields.io/github/release/cybercog/php-unicode.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/php-unicode/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/cybercog/php-unicode/tests.yml?style=flat-square" alt="Build"></a>
<a href="https://github.com/cybercog/php-unicode/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/php-unicode.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Streamline Unicode strings, code points and grapheme clusters manipulations. Object oriented implementation.

The library provides two levels of abstraction:
- **Code point level** (`CodePoint`, `UnicodeString`) — works with individual Unicode code points. Requires `ext-mbstring`.
- **Grapheme level** (`Grapheme`, `GraphemeString`) — works with user-perceived characters (grapheme clusters). Requires `ext-intl`.

## Requirements

| Class | Required Extensions |
|-------|---------------------|
| `CodePoint` | `ext-mbstring` |
| `UnicodeString` | `ext-mbstring` |
| `Grapheme` | `ext-mbstring`, `ext-intl` |
| `GraphemeString` | `ext-mbstring`, `ext-intl` |

PHP 8.1 or higher is required.

## Installation

Pull in the package through Composer.

```shell
composer require cybercog/php-unicode
```

For grapheme cluster support, install the `intl` PHP extension.

## Usage

### Code Point

```php
$codePoint = \Cog\Unicode\CodePoint::of('ÿ');

$codePoint = \Cog\Unicode\CodePoint::ofDecimal(255);

$codePoint = \Cog\Unicode\CodePoint::ofHexadecimal('U+00FF');

$codePoint = \Cog\Unicode\CodePoint::ofHtmlEntity('&yuml;');

$codePoint = \Cog\Unicode\CodePoint::ofXmlEntity('&#xff;');
```

### Represent Code Point in any format

```php
$codePoint = \Cog\Unicode\CodePoint::of('ÿ');

echo strval($codePoint); // (string) "ÿ"

echo $codePoint->toDecimal(); // (int) 255

echo $codePoint->toHexadecimal(); // (string) "U+00FF"

echo $codePoint->toHtmlEntity(); // (string) "&yuml;"

echo $codePoint->toXmlEntity(); // (string) "&#xff;"
```

### Unicode String (code point level)

```php
$string = \Cog\Unicode\UnicodeString::of('Hello');
```

`UnicodeString` object will contain a list of code points.

For example, the Unicode string "Hello" is represented by the code points:
- U+0048 (H)
- U+0065 (e)
- U+006C (l)
- U+006C (l)
- U+006F (o)

```php
echo strval($string); // (string) "Hello"

$codePointList = $string->codePointList; // list<CodePoint>
```

### Grapheme (grapheme cluster level)

Requires `ext-intl`.

```php
$grapheme = \Cog\Unicode\Grapheme::of('👨‍👩‍👧‍👦');

echo strval($grapheme); // (string) "👨‍👩‍👧‍👦"

$codePointList = $grapheme->codePointList; // list<CodePoint>
```

### Grapheme String (grapheme cluster level)

Requires `ext-intl`.

```php
$string = \Cog\Unicode\GraphemeString::of('Ае👨‍👩‍👧‍👦');

$graphemeList = $string->graphemeList; // list<Grapheme>
// 'А', 'е', '👨‍👩‍👧‍👦' — 3 graphemes (not 9 code points)

echo strval($string); // (string) "Ае👨‍👩‍👧‍👦"
```

### Real-world examples

#### Convert a character to all supported formats

```php
$codePoint = \Cog\Unicode\CodePoint::of('©');

echo $codePoint->toDecimal();     // 169
echo $codePoint->toHexadecimal(); // "U+00A9"
echo $codePoint->toHtmlEntity();  // "&copy;"
echo $codePoint->toXmlEntity();   // "&#xA9;"
```

#### Round-trip between entity formats

```php
$cp = \Cog\Unicode\CodePoint::ofHtmlEntity('&hearts;');

echo $cp->toXmlEntity(); // "&#x2665;"
echo $cp->toDecimal();   // 9829

$cp2 = \Cog\Unicode\CodePoint::ofDecimal($cp->toDecimal());
echo strval($cp2); // "♥"
```

#### Inspect code points in a string

```php
$string = \Cog\Unicode\UnicodeString::of('café');

foreach ($string->codePointList as $cp) {
    echo $cp->toHexadecimal() . ' ';
}
// U+0063 U+0061 U+0066 U+00E9
```

#### Code points vs. graphemes — why it matters

```php
// Flag emoji: 2 code points, but 1 visible character
$flag = \Cog\Unicode\UnicodeString::of('🇦🇶');
echo count($flag->codePointList); // 2

$flag = \Cog\Unicode\GraphemeString::of('🇦🇶');
echo count($flag->graphemeList); // 1

// Family emoji: 7 code points (persons + ZWJ), 1 visible character
$family = \Cog\Unicode\GraphemeString::of('👨‍👩‍👧‍👦');
echo count($family->graphemeList); // 1

$familyGrapheme = $family->graphemeList[0];
echo count($familyGrapheme->codePointList); // 7
```

#### Detect combining marks

```php
$acute = \Cog\Unicode\CodePoint::of("\u{0301}"); // combining acute accent
echo $acute->isCombining(); // true

$a = \Cog\Unicode\CodePoint::of('A');
echo $a->isCombining(); // false
```

## Why this library?

PHP provides `mb_*` and `grapheme_*` functions, but they are procedural and return raw strings. This library wraps them in immutable, type-safe value objects with two key benefits:

- **Two levels of abstraction.** `CodePoint` / `UnicodeString` work with individual Unicode code points. `Grapheme` / `GraphemeString` work with user-perceived characters (grapheme clusters). Choose the right level for your use case instead of mixing `mb_strlen` and `grapheme_strlen` calls.
- **Format conversion.** `CodePoint` converts between character, decimal, hexadecimal (`U+XXXX`), HTML entity, and XML entity formats in a single object. No need to chain `mb_ord`, `dechex`, `htmlentities` manually.

```php
// Procedural
$char = '©';
$dec = mb_ord($char);
$hex = 'U+' . strtoupper(sprintf('%04X', $dec));
$html = htmlentities($char, ENT_HTML5 | ENT_QUOTES);

// With this library
$cp = \Cog\Unicode\CodePoint::of('©');
$dec = $cp->toDecimal();
$hex = $cp->toHexadecimal();
$html = $cp->toHtmlEntity();
```

## License

- `PHP Unicode` package is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

## About CyberCog

[CyberCog] is a Social Unity of enthusiasts. Research the best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)

<a href="https://cybercog.su"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>

[Anton Komarev]: https://komarev.com
[CyberCog]: https://cybercog.su
