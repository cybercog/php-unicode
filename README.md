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

echo $grapheme->codePointCount(); // (int) 7

echo $grapheme->isSingleCodePoint(); // (bool) false

$codePointList = $grapheme->codePointList(); // list<CodePoint>
```

### Grapheme String (grapheme cluster level)

Requires `ext-intl`.

```php
$string = \Cog\Unicode\GraphemeString::of('Ае👨‍👩‍👧‍👦');

$graphemeList = $string->graphemeList; // list<Grapheme>
// 'А', 'е', '👨‍👩‍👧‍👦' — 3 graphemes (not 9 code points)

echo strval($string); // (string) "Ае👨‍👩‍👧‍👦"
```

## License

- `PHP Unicode` package is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

## About CyberCog

[CyberCog] is a Social Unity of enthusiasts. Research the best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)

<a href="https://cybercog.su"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>

[Anton Komarev]: https://komarev.com
[CyberCog]: https://cybercog.su
