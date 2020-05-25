# WPLang for Roots

![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/raph-topo/wplang?sort=semver)
![License](https://img.shields.io/github/license/raph-topo/wplang)
![GitHub last commit](https://img.shields.io/github/last-commit/raph-topo/wplang)
![GitHub issues](https://img.shields.io/github/issues/raph-topo/wplang)
![GitHub pull requests](https://img.shields.io/github/issues-pr/raph-topo/wplang)

Composer plugin to download translation files for WordPress core, plugins and themes from wordpress.org.
Optimized for the [Roots](https://roots.io/) stack (Bedrock).

## Installation

Edit your `composer.json` file to include:
```json
{
    "repositories": [
      {
        "type": "vcs",
        "url": "https://github.com/raph-topo/wplang"
      }
    ],
    "require": {
      "raph-topo/wplang": "^0.3"
    },
    "extra": {
        "wordpress-languages": [ "fr_FR" ],
        "wordpress-language-dir": "web/app/languages"
    }
}
```

Composer will try to pull down translations for all your packages from wordpress.org every time you install or update a package.

To force-update translations for already installed packages, delete the following folders:
- `web/app/plugins` — except `vendor/.gitkeep`
- `web/app/languages`

Then, run:
```bash
$ composer install
```

## Credits

This package Started as a fork of Angry Creative’s [Composer Auto Language Updates](https://github.com/Angrycreative/composer-plugin-language-update), but has since been rewritten. It is not compatible with the original package at all, but this package would probably not have existed with the first. There are probably some code in this package that the original author will still recognize. Integrates compatibility changes made by [Mirai](https://github.com/mirai-wordpress/wplang).
