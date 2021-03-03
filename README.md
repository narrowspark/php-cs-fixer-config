<h1 align="center">Narrowspark php-cs-fixer Config</h1>
<p align="center">
    <a href="https://github.com/narrowspark/php-cs-fixer-config/releases"><img src="https://img.shields.io/packagist/v/narrowspark/php-cs-fixer-config.svg?style=flat-square"></a>
    <a href="https://php.net/"><img src="https://img.shields.io/badge/php-%5E8.0.0-8892BF.svg?style=flat-square"></a>
    <a href="https://codecov.io/gh/narrowspark/php-cs-fixer-config"><img src="https://img.shields.io/codecov/c/github/narrowspark/php-cs-fixer-config/main.svg?style=flat-square"></a>
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

Create a configuration file `.php_cs` in the root of your project:

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

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/php_cs.cache');

return $config;
```

### Git

All configuration examples use the caching feature, and if you want to use it as well, you add the cache directory to `.gitignore`:

```diff
+ /.build/
 /vendor/
```

:bulb: personally, I prefer to use a `.build` directory for storing build artifacts.

### Configuration with header

:bulb: optionally specify a header:

```diff
<?php
declare(strict_types=1);

use Narrowspark\CS\Config\Config;

+$header = <<<EOF
+Copyright (c) 2020 Narrowspark
+
+For the full copyright and license information, please view
+the LICENSE file that was distributed with this source code.
+EOF;

-$config = new Narrowspark\CS\Config\Config();
+$config = new Narrowspark\CS\Config\Config($header);

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/php_cs.cache');

return $config;
```

This will turn on and configure the [`HeaderCommentFixer`](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/v2.1.1/src/Fixer/Comment/HeaderCommentFixer.php), so that
file headers will be added to PHP files, for example:

Configuration with override rules
-------------

:bulb: optionally override rules from a rule set by passing in an array of rules to be merged in:

```diff
<?php
declare(strict_types=1);

use Narrowspark\CS\Config\Config;

- $config = new Config();
+ $config = new Config(null /* if you dont need a header */, [
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

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/php_cs.cache');

return $config;
```

Composer
-------------
If you like [`composer` scripts](https://getcomposer.org/doc/articles/scripts.md), add a `coding-standards` script to `composer.json`:

```diff
 {
   "name": "foo/bar",
   "require": {
     "php": "^7.3",
   },
   "require-dev": {
     "narrowspark/php-cs-fixer-config": "~1.0.0"
+  },
+  "scripts": {
+    "cs:check": [
+      "mkdir -p .build/php-cs-fixer",
+      "php-cs-fixer fix --diff --diff-format=udiff --verbose"
+    ]
   }
 }
```

Run

```
$ composer cs:check
```

To automatically fix coding standard violations.

Travis
-------------

If you like [Travis CI](https://travis-ci.com), add a `coding-standards` stage to your jobs:

```diff
 language: php

 cache:
   directories:
     - $HOME/.composer/cache
+    - .build/php-cs-fixer

 jobs:
   include:
+    - stage: "Coding Standards"
+
+      php: 7.3
+
+      install:
+        - composer install --no-interaction --no-progress --no-suggest
+
+      before_script:
+        - mkdir -p .build/php-cs-fixer
+
+      script:
+        - vendor/bin/php-cs-fixer fix --config=.php_cs --diff --dry-run --verbose
```

### GitHub Actions

If you like [GitHub Actions](https://github.com/features/actions), add a `coding-standards` job to your workflow:

```diff
 on:
   pull_request:
   push:
     branches:
       - master
     tags:
       - "**"

 name: "Continuous Integration"

 jobs:
+  coding-standards:
+    name: "Coding Standards"
+
+    runs-on: ubuntu-latest
+
+    steps:
+      - name: "Checkout"
+        uses: actions/checkout@v1.1.0
+
+      - name: "Disable Xdebug"
+        run: php7.3 --ini | grep xdebug | sed 's/,$//' | xargs sudo rm
+
+      - name: "Cache dependencies installed with composer"
+        uses: actions/cache@v1.0.2
+        with:
+          path: ~/.composer/cache
+          key: php7.3-composer-locked-${{ hashFiles('**/composer.lock') }}
+          restore-keys: |
+            php7.3-composer-locked-
+
+      - name: "Install locked dependencies with composer"
+        run: php7.3 $(which composer) install --no-interaction --no-progress --no-suggest
+
+      - name: "Create cache directory for friendsofphp/php-cs-fixer"
+        run: mkdir -p .build/php-cs-fixer
+
+      - name: "Cache cache directory for friendsofphp/php-cs-fixer"
+        uses: actions/cache@v1.0.2
+        with:
+          path: ~/.build/php-cs-fixer
+          key: php7.3-php-cs-fixer-${{ hashFiles('**/composer.lock') }}
+          restore-keys: |
+            php7.3-php-cs-fixer-
+
+      - name: "Run friendsofphp/php-cs-fixer"
+        run: php7.3 vendor/bin/php-cs-fixer fix --config=.php_cs --diff --diff-format=udiff --dry-run --verbose
```

Testing
-------------

``` bash
$ vendor/bin/phpunit
```

Contributing
------------

If you would like to help take a look at the [list of issues](https://github.com/narrowspark/php-cs-fixer-config/issues) and check our [Contributing](.github/CONTRIBUTING.md) guild.

> **Note:** please note that this project is released with a Contributor Code of Conduct. By participating in this project you agree to abide by its terms.

Credits
-------------

- [Daniel Bannert](https://github.com/prisis)
- [Andreas Möller](https://github.com/localheinz)
- [All Contributors](https://github.com/narrowspark/php-cs-fixer-config/graphs/contributors)

License
-------------

The Narrowspark http-emitter is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT)
