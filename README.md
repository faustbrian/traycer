[![GitHub Workflow Status][ico-tests]][link-tests]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

------

# traycer

Generate and attach tracing identifiers to Laravel request context.

## Requirements

> **Requires [PHP 8.5+](https://php.net/releases/)**

## Installation

```bash
composer require cline/traycer
```

## Usage

```php
// bootstrap/app.php (Laravel 12)
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\Cline\Traycer\Http\Middleware\AddTracingIdentifier::class);
})
```

```php
// config/traycer.php
'strategy' => 'idempotency',
'context_key' => 'tracingIdentifier',
```

The default idempotency strategy hashes `$request->json()` to produce a stable tracing identifier.

```php
// Use Mint (optional) after: composer require cline/mint
'strategy' => 'mint',
'strategies' => [
    'mint' => [
        'class' => \Cline\Traycer\Strategies\MintStrategy::class,
        'type' => 'ulid',
    ],
],
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please use the [GitHub security reporting form][link-security] rather than the issue queue.

## Credits

- [Brian Faust][link-maintainer]
- [All Contributors][link-contributors]

## License

The MIT License. Please see [License File](LICENSE.md) for more information.

[ico-tests]: https://git.cline.sh/faustbrian/traycer/actions/workflows/quality-assurance.yaml/badge.svg
[ico-version]: https://img.shields.io/packagist/v/cline/traycer.svg
[ico-license]: https://img.shields.io/badge/License-MIT-green.svg
[ico-downloads]: https://img.shields.io/packagist/dt/cline/traycer.svg

[link-tests]: https://git.cline.sh/faustbrian/traycer/actions
[link-packagist]: https://packagist.org/packages/cline/traycer
[link-downloads]: https://packagist.org/packages/cline/traycer
[link-security]: https://git.cline.sh/faustbrian/traycer/security
[link-maintainer]: https://git.cline.sh/faustbrian
[link-contributors]: ../../contributors
