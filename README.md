# log-browser-check

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Style CI][ico-styleci]][link-styleci]
[![Code Coverage][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A simple web server log checker to monitor the browser diversity on websites.

## Structure

```
src/
tests/
vendor/
```

## Install

Via Composer

``` bash
$ composer require pxgamer/log-browser-check
```

## Usage

### Supported logs

Server | Supported?
------ | ----------
IIS    | Yes
Apache | No
Nginx  | No

```php
use pxgamer\LogBrowserChecker\Config;
use pxgamer\LogBrowserChecker\IIS;

$config = new Config([
    'session_column' => 7,
    'ip_column' => 6,
    'root_dir' => __DIR__ . '/logs',
    'site_name' => 'test.com',
    'ignored_ips' => ['127.0.0.1']
]);

$browserCheck = new IIS($config);
$browserCheck->findFiles();
$browserCheck->execute();
		
// Retrieve an array of browsers and their usage counts
$browserCheck->getBrowserStats();

// Retrieve an array of unique session ids
$browserCheck->getSessionsIds();

// Retrieve an array of unique user agents
$browserCheck->getUserAgents();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email owzie123@gmail.com instead of using the issue tracker.

## Credits

- [pxgamer][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/pxgamer/log-browser-check.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pxgamer/log-browser-check/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/69864945/shield
[ico-code-quality]: https://img.shields.io/codecov/c/github/pxgamer/log-browser-check.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pxgamer/log-browser-check.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pxgamer/log-browser-check
[link-travis]: https://travis-ci.org/pxgamer/log-browser-check
[link-styleci]: https://styleci.io/repos/69864945
[link-code-quality]: https://codecov.io/gh/pxgamer/log-browser-check
[link-downloads]: https://packagist.org/packages/pxgamer/log-browser-check
[link-author]: https://github.com/pxgamer
[link-contributors]: ../../contributors
