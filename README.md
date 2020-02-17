<h1 align="center">Narrowspark php-cs-fixer Config</h1>
<p align="center">
    <a href="https://github.com/narrowspark/php-cs-fixer-config/releases"><img src="https://img.shields.io/packagist/v/narrowspark/php-cs-fixer-config.svg?style=flat-square"></a>
    <a href="https://php.net/"><img src="https://img.shields.io/badge/php-%5E7.3.0-8892BF.svg?style=flat-square"></a>
    <a href="https://codecov.io/gh/narrowspark/php-cs-fixer-config"><img src="https://img.shields.io/codecov/c/github/narrowspark/php-cs-fixer-config/master.svg?style=flat-square"></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
</p>
This repository provides a configuration for https://github.com/FriendsOfPHP/PHP-CS-Fixer, which
we use to verify and enforce a single coding standard for PHP code within Narrowspark and Anolilab.

Installation
-------------

Via Composer

``` bash
$ composer require narrowspark/php-cs-fixer-config
```

Usage
-------------

Create a configuration file '.php_cs' in the root of your project:

```php
<?php
declare(strict_types=1);
use Narrowspark\CS\Config\Config;

$config = new Config();
$config->getFinder()
    ->files()
    ->in(__DIR__)
    ->exclude('.build')
    ->exclude('vendor')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

$config->setCacheFile($cacheDir . '/.php_cs.cache');

return $config;

```

:bulb: Optionally, you can specify a header comment to use, which will automatically enable the `header_comment` fixer:

```php
$header = <<<EOF
Copyright (c) 2016 Narrowspark

For the full copyright and license information, please view
the LICENSE file that was distributed with this source code.
EOF;

$config = new Narrowspark\CS\Config\Config($header);
```

Configuration with override rules
-------------

:bulb: Optionally override rules from a rule set by passing in an array of rules to be merged in:

```php
<?php
declare(strict_types=1);

use Narrowspark\CS\Config\Config;

$config = new Config(null /* if you dont need a header */, [
    'mb_str_functions' => false,
    'strict_comparison' => false,
]);
$config->getFinder()
    ->files()
    ->in(__DIR__)
    ->exclude('.build')
    ->exclude('vendor')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

$config->setCacheFile($cacheDir . '/.php_cs.cache');

return $config;
```

Git
-------------
Add `.php_cs.cache` (this is the cache file created by `php-cs-fixer`) to `.gitignore`:

```
vendor/
.php_cs.cache
```

Travis
-------------
Update your `.travis.yml` to cache the `php_cs.cache` file:

```yml
cache:
  directories:
    - $HOME/.php-cs-fixer
```

Then run `php-cs-fixer` in the `script` section:

```yml
script:
  - ./vendor/bin/php-cs-fixer fix --config=.php_cs --verbose --diff --dry-run
```
If you only want to run `php-cs-fixer` on one PHP version, update your build matrix and use a condition:

```yml
matrix:
  include:
    - php: 7.1
      env: WITH_CS=true
    - php: 7.2
      env: WITH_COVERAGE=true

script:
  - if [[ "$WITH_CS" == "true" ]]; then ./vendor/bin/php-cs-fixer fix --config=.php_cs --verbose --diff --dry-run; fi
```

Composer
-------------
Update ``composer.json`` script section with this line ``"cs": "php-cs-fixer fix"``.
To run php-cs-fixer call ``composer cs``.

```json
{
    "scripts": {
        "cs": "php-cs-fixer fix"
    }
}
```


Testing
-------------

``` bash
$ vendor/bin/phpunit
```

Contributing
------------

If you would like to help take a look at the [list of issues](http://github.com/narrowspark/php-cs-fixer-config/issues) and check our [Contributing](CONTRIBUTING.md) guild.

> **Note:** Please note that this project is released with a Contributor Code of Conduct. By participating in this project you agree to abide by its terms.

Credits
-------------

- [Daniel Bannert](https://github.com/prisis)
- [Andreas Möller](https://github.com/localheinz)
- [All Contributors](../../contributors)

License
-------------

The Narrowspark http-emitter is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
