# AGENTS.md

This file provides guidance to LLM Agents when working with code in this repository.

## Project Overview

PHP library for object-oriented Unicode string and character manipulation. Namespace: `Cog\Unicode`. Requires PHP 8.1+ with `ext-mbstring`. Optional `ext-intl` for grapheme cluster support (`Grapheme`, `GraphemeString`).

## Commands

All commands run through Docker Compose. Use any PHP service (php81–php85):

```bash
# Install dependencies
docker compose run php85 composer install

# Run all tests
docker compose run php85 vendor/bin/phpunit

# Run tests with readable output
docker compose run php85 vendor/bin/phpunit --testdox

# Run a single test file
docker compose run php85 vendor/bin/phpunit test/Unit/CodePointTest.php

# Run a single test method
docker compose run php85 vendor/bin/phpunit --filter testMethodName

# Run static analysis
docker compose run php85 vendor/bin/phpstan analyse
```

Static analysis uses PHPStan at level 9 with strict-rules. Config: `phpstan.neon.dist`.

## Architecture

Four final, immutable classes with private constructors and static factory methods:

**Code point level (requires `ext-mbstring`):**
- **`CodePoint`** (`src/CodePoint.php`): Single Unicode code point. Created via `of()`, `ofDecimal()`, `ofHexadecimal()`, `ofHtmlEntity()`, `ofXmlEntity()`. Converts to decimal, hex (U+XXXX), HTML entity, XML entity. Validates code points (0x0000–0x10FFFF).
- **`UnicodeString`** (`src/UnicodeString.php`): Sequence of `CodePoint` objects. Created via `of()` or `ofCodePointList()`. Splits strings by code points using `preg_split`.

**Grapheme level (requires `ext-intl`):**
- **`Grapheme`** (`src/Grapheme.php`): Single grapheme cluster (user-perceived character), contains `list<CodePoint>`. Created via `of()` or `ofCodePointList()`.
- **`GraphemeString`** (`src/GraphemeString.php`): Sequence of `Grapheme` objects. Created via `of()` or `ofGraphemeList()`. Splits strings by grapheme clusters using `grapheme_*` functions.

All classes use `declare(strict_types=1)` and readonly properties.

## Testing

Tests are in `test/Unit/` using PHPUnit with `#[DataProvider]` attributes for parameterized testing. CI tests against PHP 8.1–8.5 with both `prefer-lowest` and `prefer-stable` dependency sets.

## Code Style

- 4-space indentation, UTF-8, LF line endings (see `.editorconfig`)
- YAML files use 2-space indentation
