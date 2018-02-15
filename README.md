# Logz.io Monolog integration

[![Latest Stable Version](https://poser.pugx.org/inpsyde/logzio-monolog/v/stable)](https://packagist.org/packages/inpsyde/logzio-monolog) 
[![Project Status](http://opensource.box.com/badges/active.svg)](http://opensource.box.com/badges) 
[![Build Status](https://travis-ci.com/inpsyde/logzio-monolog.svg?branch=master)](http://travis-ci.com/inpsyde/logzio-monolog) 
[![License](https://poser.pugx.org/inpsyde/logzio-monolog/license)](https://packagist.org/packages/inpsyde/logzio-monolog)


This package allows you to integrate [Logz.io](https://logz.io) into Monolog.

## Installation

Install the latest version with

```
$ composer require inpsyde/logzio-monolog
```

## Why is this an extra package?

There is already a [pull request #1009](https://github.com/Seldaek/monolog/pull/1009) since 06/2017 which get's no feedback from Monolog contributors. Since we still want to use Logz.io with Monolog, we decided to exclude this pull request into an own repository for easier usage.

This repository will be depracted when our pull request is accepted and merged.

## Basic Usage

```php
<?php

use Monolog\Logger;
use Inpsyde\LogzIoMonolog\Handler\LogzIoHandler;
use Inpsyde\LogzIoMonolog\Transport\HttpTransport;

$token = '<your-token>';

// create a log channel
$log = new Logger('name');
$log->pushHandler(new LogzIoHandler(new HttpTransport($token)));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
```

