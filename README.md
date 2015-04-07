[![Build Status](https://travis-ci.org/juliangut/slim-booboo-middleware.svg?branch=master)](https://travis-ci.org/juliangut/slim-booboo-middleware)
[![Code Climate](https://codeclimate.com/github/juliangut/slim-booboo-middleware/badges/gpa.svg)](https://codeclimate.com/github/juliangut/slim-booboo-middleware)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/juliangut/slim-booboo-middleware/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/juliangut/slim-booboo-middleware/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/juliangut/slim-booboo-middleware/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/juliangut/slim-booboo-middleware/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/juliangut/slim-booboo-middleware/v/stable.svg)](https://packagist.org/packages/juliangut/slim-booboo-middleware)
[![Total Downloads](https://poser.pugx.org/juliangut/slim-booboo-middleware/downloads.svg)](https://packagist.org/packages/juliangut/slim-booboo-middleware)

# Juliangut Slim Framework BooBoo handler middleware

BooBoo error handler middleware for Slim Framework.

Uses [BooBoo](https://github.com/thephpleague/booboo) error handler library by [The PHP League](http://thephpleague.com/)

## Installation

Best way to install is using [Composer](https://getcomposer.org/):

```
php composer.phar require juliangut/slim-booboo-middleware
```

Then require_once the autoload file:

```php
require_once './vendor/autoload.php';
```

## Usage

Add as middleware.
BooBoo middleware will automatically register BooBoo to handle errors.

BooBooMiddleware can handle creation of formatters.

```php
use Slim\Slim;
use Jgut\Slim\Middleware\BooBooMiddleware;

$app = new Slim();
$app->add((new BooBooMiddleware())
    ->addFormatter('command-line')
    ->addFormatter('\League\BooBoo\Formatter\NullFormatter', E_NOTICE)
);
```

But BooBoo Middleware cannot handle creation of handlers.

```php
use Slim\Slim;
use Jgut\Slim\Middleware\BooBooMiddleware;

$yourHandler = new \yourHandler();

$app = new Slim();
$app->add((new BooBooMiddleware())
    ->addFormatter('null', E_NOTICE)
    ->addHandler($yourHandler)
);
```

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/slim-booboo-middleware/issues). Have a look at existing issues before

See file [CONTRIBUTING.md](https://github.com/juliangut/slim-booboo-middleware/blob/master/CONTRIBUTING.md)

## License

### Release under BSD-3-Clause License.

See file [LICENSE](https://github.com/juliangut/slim-booboo-middleware/blob/master/LICENSE) included with the source code for a copy of the license terms

