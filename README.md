# PHP Unicode

<p align="center">
<a href="https://github.com/cybercog/php-unicode/releases"><img src="https://img.shields.io/github/release/cybercog/php-unicode.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/php-unicode/actions/workflows/tests.yml"><img src="https://img.shields.io/github/actions/workflow/status/cybercog/php-unicode/tests.yml?style=flat-square" alt="Build"></a>
<a href="https://github.com/cybercog/php-unicode/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/php-unicode.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Streamline Unicode strings and characters (code points) manipulations. Object oriented implementation.

## Installation

Pull in the package through Composer.

```shell
composer require cybercog/php-unicode
```

## Usage

### Instantiate Unicode String

```php
$compositeCharacter = \Cog\Unicode\UnicodeString::of('Hello');
```

`UnicodeString` object will contain a list of Unicode characters.

For example, the Unicode string "Hello" is represented by the code points:
- U+0048 (H)
- U+0065 (e)
- U+006C (l)
- U+006C (l)
- U+006F (o)

### Represent Unicode String

```php
$compositeCharacter = \Cog\Unicode\UnicodeString::of('Hello');

echo strval($compositeCharacter); // (string) "Hello"
```

### Instantiate Unicode Character

```php
$character = \Cog\Unicode\Character::of('ÿ');

$character = \Cog\Unicode\Character::ofDecimal(255);

$character = \Cog\Unicode\Character::ofHexadecimal('U+00FF');

$character = \Cog\Unicode\Character::ofHtmlEntity('&yuml;');

$character = \Cog\Unicode\Character::ofXmlEntity('&#xff;');
```

### Represent Unicode Character in any format

```php
$character = \Cog\Unicode\Character::of('ÿ');

echo strval($character); // (string) "ÿ"

echo $character->toDecimal(); // (int) 255

echo $character->toHexadecimal(); // (string) "U+00FF"

echo $character->toHtmlEntity(); // (string) "&yuml;"

echo $character->toXmlEntity(); // (string) "&#xff;"
```

## License

- `PHP Unicode` package is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

## About CyberCog

[CyberCog] is a Social Unity of enthusiasts. Research the best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)

<a href="https://cybercog.su"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>

[Anton Komarev]: https://komarev.com
[CyberCog]: https://cybercog.su
