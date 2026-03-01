# Upgrading from v1 to v2

## Breaking Changes

### `Character` class renamed to `CodePoint`

The `Character` class has been removed and replaced with `CodePoint`.

```php
// Before
use Cog\Unicode\Character;

$char = Character::of('A');
$char = Character::ofDecimal(65);
$char = Character::ofHexadecimal('U+0041');
$char = Character::ofHtmlEntity('&amp;');
$char = Character::ofXmlEntity('&#x41;');

// After
use Cog\Unicode\CodePoint;

$codePoint = CodePoint::of('A');
$codePoint = CodePoint::ofDecimal(65);
$codePoint = CodePoint::ofHexadecimal('U+0041');
$codePoint = CodePoint::ofHtmlEntity('&amp;');
$codePoint = CodePoint::ofXmlEntity('&#x41;');
```

### `CodePoint` property renamed from `$codePoint` to `$value` and made private

The public readonly property `$codePoint` on the former `Character` class has been replaced with a private `$value` property on `CodePoint`. Use `toDecimal()` to get the integer value.

```php
// Before
$char = Character::of('A');
$decimal = $char->codePoint; // 65

// After
$codePoint = CodePoint::of('A');
$decimal = $codePoint->toDecimal(); // 65
```

### `UnicodeString` property and factory method renamed

- Property `$characterList` renamed to `$codePointList`
- Factory method `ofCharacterList()` renamed to `ofCodePointList()`
- The list type changed from `list<Character>` to `list<CodePoint>`

```php
// Before
$string = UnicodeString::ofCharacterList([
    Character::of('H'),
    Character::of('i'),
]);
$chars = $string->characterList;

// After
$string = UnicodeString::ofCodePointList([
    CodePoint::of('H'),
    CodePoint::of('i'),
]);
$codePoints = $string->codePointList;
```

### `UnicodeString::characterList()` method removed

The `characterList()` getter method has been removed. Access the `$codePointList` property directly instead.

```php
// Before
$list = $string->characterList();

// After
$list = $string->codePointList;
```

### `isCombining()` uses broader Unicode category

The `isCombining()` method now matches all Mark characters (`\p{M}`) instead of only Non-Spacing Marks (`\p{Mn}`). This includes Spacing Combining Marks (`\p{Mc}`) and Enclosing Marks (`\p{Me}`).

### `ofHexadecimal()` parsing fix

`CodePoint::ofHexadecimal()` now correctly parses the hex string by stripping the `U+` prefix before conversion. Previously `Character::ofHexadecimal()` passed the full string (including `U+`) to `hexdec()`, which silently returned `0` for most inputs.

### `ofHtmlEntity()` and `ofXmlEntity()` stricter validation

Both methods now validate entity format with a regex before decoding and reject unknown/invalid entities with `InvalidArgumentException`. Previously they accepted any string and silently returned incorrect results for malformed input.

### Surrogate code points are now rejected

`CodePoint` constructor rejects surrogate code points (U+D800–U+DFFF) with `OutOfRangeException`. The `ofHtmlEntity()` and `ofXmlEntity()` methods also reject numeric entities referencing surrogates with `InvalidArgumentException`.

### `toHtmlEntity()` behavior change

`CodePoint::toHtmlEntity()` now returns `&#xHEX;` numeric entity for characters that `htmlentities()` does not convert (e.g. `A` → `&#x41;`), instead of returning the raw character. Named entities (e.g. `&amp;`) are still returned when available. Hex digits are now uppercase.

### `toXmlEntity()` hex digits are now uppercase

`CodePoint::toXmlEntity()` now returns uppercase hex digits (e.g. `&#x41;` instead of `&#x41;` — previously it used lowercase like `&#x61;` for `a`).

## New Features

### `Stringable` interface

All classes (`CodePoint`, `UnicodeString`, `Grapheme`, `GraphemeString`) now implement `\Stringable`.

### Grapheme cluster support (requires `ext-intl`)

Two new classes for working with grapheme clusters (user-perceived characters):

**`Grapheme`** — a single grapheme cluster composed of one or more code points:

```php
use Cog\Unicode\Grapheme;

$grapheme = Grapheme::of('👨‍👩‍👧‍👦'); // family emoji — single grapheme, multiple code points
$grapheme->codePointCount(); // 7
$grapheme->isSingleCodePoint(); // false
$grapheme->codePointList; // list<CodePoint>

$grapheme = Grapheme::ofCodePointList([
    CodePoint::of('e'),
    CodePoint::ofHexadecimal('U+0301'), // combining acute accent
]);
echo $grapheme; // "é"
```

**`GraphemeString`** — a sequence of graphemes:

```php
use Cog\Unicode\GraphemeString;

$string = GraphemeString::of('Héllo 👨‍👩‍👧‍👦');
$string->graphemeList; // list<Grapheme>

$string = GraphemeString::ofGraphemeList([
    Grapheme::of('H'),
    Grapheme::of('i'),
]);
echo $string; // "Hi"
```

Both classes throw `RuntimeException` if `ext-intl` is not loaded.

### Optional `ext-intl` suggestion in composer.json

`composer.json` now includes a `suggest` entry for `ext-intl`, informing users that it is required for `Grapheme` and `GraphemeString`.

## Migration Checklist

1. Replace all `use Cog\Unicode\Character` with `use Cog\Unicode\CodePoint`
2. Replace all `Character::` static calls with `CodePoint::`
3. Replace `->codePoint` property access with `->toDecimal()`
4. Replace `->characterList` with `->codePointList`
5. Replace `->characterList()` method calls with `->codePointList` property access
6. Replace `ofCharacterList()` with `ofCodePointList()`
7. Review any code that relies on `isCombining()` — it now matches a broader set of marks
8. Review any code that relies on `toHtmlEntity()` output format — unrecognized characters now return `&#xHEX;` instead of the raw character
9. Install `ext-intl` if you want to use the new `Grapheme` and `GraphemeString` classes
