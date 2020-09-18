<p align="center">
	<img height="60px" width="60px" src="https://plugins.vdlp.nl/octobercms/icons/Vdlp.Redirect.svg" >
	<h1 align="center">Vdlp.RedirectConditions</h1>
</p>

<p align="center">
	<em>This plugin allows developers to create their own Redirect conditions.</em>
</p>

<p align="center">
	<img src="https://badgen.net/packagist/php/vdlp/oc-redirectconditions-plugin">
	<img src="https://badgen.net/packagist/license/vdlp/oc-redirectconditions-plugin">
	<img src="https://badgen.net/packagist/v/vdlp/oc-redirectconditions-plugin/latest">
	<img src="https://badgen.net/badge/cms/October%20CMS">
	<img src="https://badgen.net/badge/type/plugin">
	<img src="https://plugins.vdlp.nl/octobercms/badge/installations.php?plugin=vdlp-redirectconditions">
</p>

## What is a Redirect Condition?

When a positive match occurs in the redirect engine, all registered redirect conditions will be checked if they pass.
If one of the conditions does not pass the redirect will not take place.

A redirect condition must implement `RedirectConditionInterface`.

Each redirect condition must have:

* `getCode()` - A unique code.
* `getDescription()` - A short description.
* `getExplanation()` - A brief explanation on when or how to use it.
* `getFormConfig()` - A form configuration array.
* `passes(RedirectRule $rule, string $requestUri)` - Logic whether the condition passes with the given `$rule` and `$requestUri`.

## Requirements

- The `Vdlp.Redirect` plugin
- PHP 7.1 or higher

## Example

This plugin contains an detailed implementation example (plugin). This plugin can be found at [GitHub](https://github.com/vdlp/oc-redirectconditionsexample-plugin).

## Unit tests

To run the Unit Tests of this plugin, execute the following command from the project root:

```
vendor/bin/phpunit plugins/vdlp/redirectconditions
```
