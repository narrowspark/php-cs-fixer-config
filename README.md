# php-cs-fixer-config

[![Author](http://img.shields.io/badge/author-@anolilab-blue.svg?style=flat-square)](https://twitter.com/anolilab)
[![Total Downloads](https://img.shields.io/packagist/dt/narrowspark/php-cs-fixer-config.svg?style=flat-square)](https://packagist.org/packages/narrowspark/php-cs-fixer-config)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Master

[![Build Status](https://img.shields.io/travis/narrowspark/php-cs-fixer-config/master.svg?style=flat-square)](https://travis-ci.org/narrowspark/php-cs-fixer-config)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/narrowspark/php-cs-fixer-config.svg?style=flat-square)](https://scrutinizer-ci.com/g/narrowspark/php-cs-fixer-config/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/narrowspark/php-cs-fixer-config.svg?style=flat-square)](https://scrutinizer-ci.com/g/narrowspark/php-cs-fixer-config)

This repository provides a configuration for [`fabpot/php-cs-fixer`](http://github.com/FriendsOfPHP/PHP-CS-Fixer), which
we use to verify and enforce a single coding standard for PHP code within Narrowspark.

## Install

Via Composer

``` bash
$ composer require --dev "fabpot/php-cs-fixer:2.0.*@dev"
$ composer require narrowspark/php-cs-fixer-config
```

## Usage

Create a configuration file '.php_cs' in the root of your project:

``` php
<?php

$config = new Narrowspark\CS\Config\Config();
$config->getFinder()->in(__DIR__);

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

$config->setCacheFile($cacheDir . '/.php_cs.cache');

return $config;
```

### Git

Add `.php_cs.cache` (this is the cache file created by `php-cs-fixer`) to `.gitignore`:

```
vendor/
.php_cs.cache
```

### Travis

Update your `.travis.yml` to cache the `php_cs.cache` file:

```yml
cache:
  directories:
    - $HOME/.composer/cache
```

Then run `php-cs-fixer` in the `script` section:

```yml
script:
  - vendor/bin/php-cs-fixer fix --config-file=.php_cs --verbose --diff --dry-run
```

If you only want to run `php-cs-fixer` on one PHP version, update your build matrix and use a condition:

```yml
matrix:
  include:
    - php: 5.4
    - php: 5.5
    - php: 5.6
      env: CHECK_CS=true

script:
  - if [[ "$CHECK_CS" == "true" ]]; then vendor/bin/php-cs-fixer fix --config-file=.php_cs --verbose --diff --dry-run; fi
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Daniel Bannert](https://github.com/prisis)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
