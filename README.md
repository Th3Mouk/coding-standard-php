# Youdot Coding Standard

[![Latest Stable Version](https://img.shields.io/packagist/v/youdot/coding-standard.svg?style=flat-square&colorB=007EC6)](https://packagist.org/packages/youdot/coding-standard)
[![Build status](https://img.shields.io/travis/Youdot/coding-standard-php/master.svg?label=travis&style=flat-square)](https://travis-ci.org/Youdot/coding-standard-php)
![PHPStan](https://img.shields.io/badge/style-level%207-brightgreen.svg?style=flat-square&label=phpstan)

The Youdot Coding Standard is set of [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) rules applied to all Youdot projects.
Youdot Coding Standard is heavily based on [Doctrine Coding Standard](https://github.com/doctrine/coding-standard).

## Install

```sh
composer require youdot/coding-standard --dev
```

## Usage

```xml
<!-- Include full Youdot Coding Standard -->
<rule ref="Youdot"/>
<!-- Or include full Youdot Coding Standard for Symfony-->
<rule ref="YoudotSymfony"/>
```

## Additional Sniffs

 - SnakeCase Variable
 - Modifier Letter Apostrophe Character
 - Immutable classes through [Psalm](https://psalm.dev/)

To disable them:

```xml
<rule ref="Youdot">
    <exclude name="Youdot.NamingConventions.ValidVariableName"/>
    <exclude name="Youdot.Strings.ModifierLetterApostrophe"/>
</rule>
```

If you're using Psalm you can enable
```xml
<rule ref="Youdot.Classes.PsalmImmutable"/>
```
