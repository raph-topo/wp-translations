# WP Translations

![Packagist Version](https://img.shields.io/packagist/v/raph-topo/wp-translations)
![Packagist Stars](https://img.shields.io/packagist/stars/raph-topo/wp-translations)
![Packagist Downloads](https://img.shields.io/packagist/dt/raph-topo/wp-translations)
![GitHub last commit](https://img.shields.io/github/last-commit/raph-topo/wp-translations)
![PHP Composer](https://github.com/raph-topo/wp-translations/workflows/PHP%20Composer/badge.svg?branch=master)
![GitHub issues](https://img.shields.io/github/issues/raph-topo/wp-translations)
![GitHub pull requests](https://img.shields.io/github/issues-pr/raph-topo/wp-translations)
![License](https://img.shields.io/github/license/raph-topo/wp-translations)

Composer plugin to download translation files for WordPress core, plugins and themes from wordpress.org.

Optimized for the [Roots](https://roots.io/) stack, particularly Bedrock, but can be adapted to other setups based on Composer.

Supports Composer v2 (only).

Instructions are given relative to the Bedrock root folder, usually `site`.

## Installation

Install with:
```bash
$ composer require raph-topo/wp-translations
```

Set the following options in `composer.json`:
```json
{
    "extra": {
        "wordpress-translations": [ "fr_FR" ],
        "wordpress-languages-dir": "web/app/languages"
    }
}
```

The [Translate WordPress](https://make.wordpress.org/polyglots/teams/) page lists available locales (column *WP Locale*)

## Usage

Composer will try to install translations from through the WordPress.org API every time you install or update a package — for this package only.

**Commit the translation files** (`web/app/languages/{.,themes,plugins}/*{.mo,.po}`) and deploy them to staging and production.

## Extant projects

To force-update translations for already installed packages, delete the contents of `web/app/plugins` (keep any `.gitkeep`) and run `composer install`

## Credits

This package was started by [Angry Creative](https://github.com/Angrycreative/composer-plugin-language-update), has been rewritten by [Bjørn Johansen](https://github.com/bjornjohansen/wplang), integrates compatibility changes made by [Mirai](https://github.com/mirai-wordpress/wplang) and was updated to support Composer v2.
