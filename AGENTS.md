# AGENTS.md

This file provides guidance to LLM Agents when working with code in this repository.

## Project Overview

PHP library for object-oriented Unicode string and character manipulation. Namespace: `Cog\Unicode`. Requires PHP 8.1+ with `ext-mbstring`.

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
docker compose run php85 vendor/bin/phpunit test/Unit/CharacterTest.php

# Run a single test method
docker compose run php85 vendor/bin/phpunit --filter testMethodName
```

No linter or static analysis tool is configured.

## Architecture

Two final, immutable classes with private constructors and static factory methods:

- **`Character`** (`src/Character.php`): Single Unicode character. Created via `of()`, `ofDecimal()`, `ofHexadecimal()`, `ofHtmlEntity()`, `ofXmlEntity()`. Converts to decimal, hex (U+XXXX), HTML entity, XML entity. Validates code points (0x0000–0x10FFFF).
- **`UnicodeString`** (`src/UnicodeString.php`): Sequence of `Character` objects. Created via `of()` or `ofCharacterList()`. Splits strings using `mb_str_split`.

All classes use `declare(strict_types=1)` and readonly properties.

## Testing

Tests are in `test/Unit/` using PHPUnit with `#[DataProvider]` attributes for parameterized testing. CI tests against PHP 8.1–8.5 with both `prefer-lowest` and `prefer-stable` dependency sets.

## Code Style

- 4-space indentation, UTF-8, LF line endings (see `.editorconfig`)
- YAML files use 2-space indentation
