[![PHP version](https://img.shields.io/badge/PHP-%3E%3D8.1-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/juliangut/phpstan-config.svg?style=flat-square)](https://packagist.org/packages/juliangut/phpstan-config)
[![License](https://img.shields.io/github/license/juliangut/phpstan-config.svg?style=flat-square)](https://github.com/juliangut/phpstan-config/blob/master/LICENSE)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/phpstan-config.svg?style=flat-square)](https://packagist.org/packages/juliangut/phpstan-config/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/phpstan-config.svg?style=flat-square)](https://packagist.org/packages/juliangut/phpstan-config/stats)

# phpstan-config

Opinionated as can be configuration defaults for [PHPStan](https://github.com/phpstan/phpstan/)

## Installation

### Composer

```
composer require --dev juliangut/phpstan-config
```

If You need a specific PHPStan constraint

```
composer require --dev phpstan/phpstan:^1.11
```

## Usage

Include in your phpstan configuration file

### Without PHPUnit rules

```neon
includes:
  - %rootDir%/../../juliangut/phpstan-config/phpstan.neon

# Add your custom configuration if needed
```

### With PHPUnit rules

```neon
includes:
  - %rootDir%/../../juliangut/phpstan-config/phpstan-phpunit.neon

# Add your custom configuration if needed
```

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/phpstan-config/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/phpstan-config/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/phpstan-config/blob/master/LICENSE) included with the source code for a copy of the license terms.
