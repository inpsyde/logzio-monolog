# Logz.io Monolog integration

[![Latest Stable Version](https://poser.pugx.org/inpsyde/logzio-monolog/v/stable)](https://packagist.org/packages/inpsyde/logzio-monolog) 
[![Project Status](http://opensource.box.com/badges/active.svg)](http://opensource.box.com/badges) 
[![Build Status](https://travis-ci.com/inpsyde/logzio-monolog.svg?branch=master)](http://travis-ci.com/inpsyde/logzio-monolog) 
[![License](https://poser.pugx.org/inpsyde/logzio-monolog/license)](https://packagist.org/packages/inpsyde/logzio-monolog)


This package allows you to integrate [Logz.io](https://logz.io) into Monolog.

## Monolog support

Monolog 1, 2 and 3 will be supported in different versions:

| Monolog Version | Logzio-Monolog Branch |
|---|---|
| 1.0 - 1.25.2 | 0.x |
| >= 2.0 | 1.x |
| >= 3.0 | 2.x |

* Monolog 1.x will be supported in this package in all versions of 0.x
* Monolog 2.x will be supported in this package in all versions of 1.x

## Installation

Install the latest version with

```
$ composer require inpsyde/logzio-monolog
```

## Basic Usage

```php
<?php

use Monolog\Logger;
use Inpsyde\LogzIoMonolog\Handler\LogzIoHandler;

// create a log channel
$log = new Logger('name');
$log->pushHandler(new LogzIoHandler('<your-token>'));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
```

