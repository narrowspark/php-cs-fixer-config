# php-cs-fixer-config

[![Author](http://img.shields.io/badge/author-@anolilab-blue.svg?style=flat-square)](https://twitter.com/anolilab)
[![Total Downloads](https://img.shields.io/packagist/dt/narrowspark/php-cs-fixer-config.svg?style=flat-square)](https://packagist.org/packages/narrowspark/php-cs-fixer-config)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Master

[![Build Status](https://img.shields.io/travis/narrowspark/php-cs-fixer-config/master.svg?style=flat-square)](https://travis-ci.org/narrowspark/php-cs-fixer-config)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/narrowspark/php-cs-fixer-config.svg?style=flat-square)](https://scrutinizer-ci.com/g/narrowspark/php-cs-fixer-config/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/narrowspark/php-cs-fixer-config.svg?style=flat-square)](https://scrutinizer-ci.com/g/narrowspark/php-cs-fixer-config)

This repository provides a configuration for [`friendsofphp/php-cs-fixer`](http://github.com/FriendsOfPHP/PHP-CS-Fixer), which
we use to verify and enforce a single coding standard for PHP code within Narrowspark.

## Install

Via Composer

``` bash
$ composer require narrowspark/php-cs-fixer-config
```

## Usage

Create a configuration file '.php_cs' in the root of your project:

``` php
<?php
use Narrowspark\CS\Config\Config;

$config = new Config();
$config->getFinder()
    ->files()
    ->in(__DIR__)
    ->exclude('build')
    ->exclude('vendor')
    ->exclude('tests')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

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
  - vendor/bin/php-cs-fixer fix --config=.php_cs --verbose --diff --dry-run
```

### StyleCi
If you using [StyleCi](https://styleci.io) just copy [.styleci.yml](.styleci.yml) to you repository and enable styleci. Or copy this setting to [StyleCi](https://styleci.io)

~~~yml
preset: recommended

risky: true

linting: true

enabled:
  - align_equals
  - combine_consecutive_unsets
  - concat_with_spaces
  - const_visibility_required
  - declare_strict_types
  - dir_constant
  - echo_to_print
  - ereg_to_preg
  - mb_str_functions
  - modernize_types_casting
  - no_blank_lines_after_return
  - no_trailing_whitespace_in_comment
  - no_blank_lines_before_namespace
  - no_empty_comment
  - no_unneeded_control_parentheses
  - no_unreachable_default_argument_value
  - no_unused_imports
  - no_useless_else
  - ordered_class_elements
  - php_unit_construct
  - php_unit_dedicate_assert
  - phpdoc_add_missing_param_annotation
  - pow_to_exponentiation
  - protected_to_private
  - random_api_migration
  - return_type_declaration

disabled:
  - blank_line_after_opening_tag
  - class_keyword_remove
  - phpdoc_no_empty_return
  - self_accessor

finder:
  exclude:
    - "build"
    - "vendor"
    - "tests"
  name:
    - "*.php"

~~~

## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [refinery29](https://github.com/refinery29/php-cs-fixer-config)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
