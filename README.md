# PhpSpec Skip Example Extension

This PhpSpec extension allows to skip example through user-friendly annotations.
[![Run Tests](https://github.com/BabDev/PhpSpecSkipExampleExtension/actions/workflows/run-tests.yml/badge.svg?branch=1.x)](https://github.com/BabDev/PhpSpecSkipExampleExtension/actions/workflows/run-tests.yml)

## Installation

Once you have installed PhpSpec (following the documentation on [the official website](http://www.phpspec.net)), you can install this extension using the following [Composer](https://getcomposer.org/) command:

```bash
composer require babdev/phpspec-skip-example-extension
```

## Configuration

You can now activate the extension by creating a `phpspec.yml` file at the root of your project:

``` yaml
extensions:
    Akeneo\SkipExampleExtension: ~
```

## Usage

### @require <class or interface>

The `@require` annotation can be used on the spec class or any example method. If a requirement is missing from the spec, all examples will be skipped. If a requirement is missing from an example, only that example will be skipped.

```php
/**
 * @require Vendor\Builder\ToolInterface
 */
class BridgeBuilderSpec extends ObjectBehavior
{
    // Will be skipped if the Vendor\Builder\ToolInterface interface does not exist
    function it_builds_a_bridge()
    {
    }

    /**
     * @require Vendor\Builder\ConcreteTruck
     */
    // Will be skipped if the Vendor\Builder\ToolInterface interface or Vendor\Builder\ConcreteTruck class does not exist
    function it_builds_the_road()
    {
    }

    //...
}
```

## Contributions

Feel free to contribute to this extension if you find some interesting ways to improve it!
