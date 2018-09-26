# Changelog

## Unreleased
* Added `ext-curl` as dependency to composer.json #1
* Updated code formatting to latest inpsyde/php-coding-standard #3
* BREAKING: Added parameter type hints to `LogzIoHandler::__construct(..., int $level, ...)` and `LogzIoHandler::send(string $data)` according to #3

## 0.2
* BREAKING: Removed Transport domain to keep it simple
* Removed «Suggest» section from composer.json

## 0.1
* Initial release
