# Changelog

## 0.4.1
* Added compatibility for PHP >= 8

## 0.4
* Implement support to ship logs to listener-eu #8

## 0.3
* Travis PHP 7.3 #7
* Improved Codestyle and smaller fixes #6
* Updated wrongly used dockblock description for $type
* Added `ext-json` as dependency to composer.json #6
* Added `ext-curl` as dependency to composer.json #1
* Sanitized `context` and `extra` in record to remove empty values which causes errors in logz.io.

## 0.2
* BREAKING: Removed Transport domain to keep it simple
* Removed «Suggest» section from composer.json

## 0.1
* Initial release
