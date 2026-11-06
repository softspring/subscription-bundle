# Subscription Bundle

[![Latest Stable](https://img.shields.io/packagist/v/softspring/subscription-bundle?label=stable&style=flat-square)](https://packagist.org/packages/softspring/subscription-bundle)
[![Latest Unstable](https://img.shields.io/packagist/v/softspring/subscription-bundle?label=unstable&style=flat-square&include_prereleases)](https://packagist.org/packages/softspring/subscription-bundle)
[![License](https://img.shields.io/packagist/l/softspring/subscription-bundle?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/packagist/dependency-v/softspring/subscription-bundle/php?style=flat-square)](composer.json)
[![Downloads](https://img.shields.io/packagist/dt/softspring/subscription-bundle?style=flat-square)](https://packagist.org/packages/softspring/subscription-bundle)
[![CI](https://img.shields.io/github/actions/workflow/status/softspring/subscription-bundle/ci.yml?branch=6.0&style=flat-square&label=CI)](https://github.com/softspring/subscription-bundle/actions/workflows/ci.yml)
[![Coverage](https://img.shields.io/codecov/c/github/softspring/subscription-bundle?branch=6.0&style=flat-square)](https://app.codecov.io/gh/softspring/subscription-bundle/tree/6.0)

Subscription plans, subscription lifecycle, upgrade flows, and admin subscription management for Symfony applications.

## Documentation

[Armonic documentation](https://armonic.softspring.es/6.0/bundles/subscription-bundle)

## What It Provides

- subscription plan and product model contracts with Doctrine mapping support
- subscription creation, trial, upgrade, cancellation, and reactivation flows
- admin controllers for plans, products, and subscriptions
- Stripe-oriented integration points through the platform layer
- Twig templates and translations for customer and admin subscription screens

## Armonic

This package is part of [Armonic](https://softspring.es/en/armonic).

## Package Files

- [Features](FEATURES.md)
- [Contributing](CONTRIBUTING.md)
- [Security](SECURITY.md)

## Contributing

Use the standard package commands before sending changes:

```bash
composer fix
composer test
composer test-bc
```

[See the contributing guide](CONTRIBUTING.md).

[Report issues](https://github.com/softspring/subscription-bundle/issues) and [send Pull Requests](https://github.com/softspring/subscription-bundle/pulls)

## Security

Please report vulnerabilities privately. See [SECURITY.md](SECURITY.md).

## License

This package is free and released under the [AGPL-3.0 license](LICENSE).
