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

