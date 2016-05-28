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
preset: psr2

risky: false

linting: true

enabled:
  - concat_with_spaces
  - function_typehint_space
  - newline_after_open_tag
  - no_blank_lines_before_namespace
  - ordered_use
  - phpdoc_order
  - short_array_syntax
  - alias_functions
  - array_element_no_space_before_comma
  - array_element_white_space_after_comma
  - double_arrow_multiline_whitespaces
  - duplicate_semicolon
  - empty_return
  - extra_empty_lines
  - include
  - list_commas
  - method_separation
  - multiline_array_trailing_comma
  - namespace_no_leading_whitespace
  - new_with_braces
  - no_blank_lines_after_class_opening
  - no_empty_lines_after_phpdocs
  - object_operator
  - operators_spaces
  - phpdoc_align
  - phpdoc_indent
  - phpdoc_inline_tag
  - phpdoc_no_access
  - phpdoc_no_empty_return
  - phpdoc_no_package
  - phpdoc_scalar
  - phpdoc_separation
  - phpdoc_to_comment
  - phpdoc_trim
  - phpdoc_types
  - phpdoc_type_to_var
  - phpdoc_var_without_name
  - remove_leading_slash_use
  - remove_lines_between_uses
  - return
  - short_bool_cast
  - single_array_no_trailing_comma
  - single_quote
  - spaces_after_semicolon
  - spaces_before_semicolon
  - spaces_cast
  - standardize_not_equal
  - ternary_spaces
  - trim_array_spaces
  - unneeded_control_parentheses
  - whitespacy_lines

finder:
  exclude:
    - "build"
    - "vendor"
    - "tests"
  name:
    - "*.php"
~~~

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [refinery29](https://github.com/refinery29/php-cs-fixer-config)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
