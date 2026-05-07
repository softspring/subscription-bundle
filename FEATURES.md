# Subscription Bundle Features

Functional definition for `softspring/subscription-bundle`.

## Purpose

- Provide reusable subscription domain contracts and Doctrine mappings for Symfony applications.
- Connect local subscription models with external platform adapters, especially Stripe-oriented flows.
- Expose customer and admin subscription workflows without forcing a full application skeleton.

## Main Features

- Registers model contracts, default entities, Doctrine mappings, and target entity resolution for plans, products, subscriptions, subscription items, and subscription transitions.
- Provides managers to create, sync, trial, upgrade, cancel, reactivate, and persist subscriptions and related catalog data.
- Ships admin plan, product, and subscription screens using `softspring/crudl-controller`.
- Includes customer-facing subscription overview, detail, and upgrade flows.
- Exposes subscription event hooks for subscribe, trial, and upgrade lifecycle stages.
- Includes Twig templates and translation files for the bundled UI.

## Integration And Extension

- Can be integrated with application-specific entities by configuring the bundle model classes.
- Can be extended with custom plan, product, subscription, subscription item, invoice, and transition implementations.
- Integrates with `softspring/customer-bundle` for customer resolution and with `softspring/platform-bundle` for platform-specific subscription adapters.
- Can be wired into admin role hierarchies through the provided security config import.

## Expected Capabilities

- Must work with the supported dependency matrix of this line, including Symfony `6.4`, `7.x`, and `8.x`.
- Must keep both regular and lowest dependency validation workflows working (`composer test` and `composer test-bc`).
- Must keep subscription lifecycle flows, Doctrine mappings, admin screens, and platform adapter integration stable across minor releases in the same line.
