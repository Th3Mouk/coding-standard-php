# Youdot Coding Standard

[![Latest Stable Version](https://img.shields.io/packagist/v/youdot/coding-standard.svg)](https://packagist.org/packages/youdot/coding-standard)

The Youdot Coding Standard are set of [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) rules applied to all Youdot projects. Youdot Coding Standard is heavily based on [Doctrine Coding Standard](https://github.com/doctrine/coding-standard).

## Usage

Install the sniffer

`composer req youdot/coding-standard --dev`

Copy the configuration

`cp vendor/youdot/coding-standard/phpcs.xml.dist phpcs.xml.dist`

Then modify the `phpcs.xml.dist` to adapt your rules and configuration on your need.

Sniff the code

`vendor/bin/phpcs`

Fix automatically some errors

`vendor/bin/phpcbf`
