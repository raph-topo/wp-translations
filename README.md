# WP Translations

![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/raph-topo/wp-translations?sort=semver)
![License](https://img.shields.io/github/license/raph-topo/wp-translations)
![GitHub last commit](https://img.shields.io/github/last-commit/raph-topo/wp-translations)
![GitHub issues](https://img.shields.io/github/issues/raph-topo/wp-translations)
![GitHub pull requests](https://img.shields.io/github/issues-pr/raph-topo/wp-translations)

Composer plugin to download translation files for WordPress core, plugins and themes from wordpress.org.

Optimized for the [Roots](https://roots.io/) stack, particularly Bedrock, but can be adapted to other setups based on Composer.

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
      "raph-topo/wplang": "^1.0"
    },
    "extra": {
        "wordpress-languages": [ "fr_FR" ],
        "wordpress-language-dir": "web/app/languages"
    }
}
```

Composer will try to pull down translations for all your packages from wordpress.org every time you install or update a package.

To force-update translations for already installed packages, delete the following folders:
- `web/app/plugins` — except `web/app/plugins/.gitkeep`
- `web/app/languages`

Then, run:
```bash
$ composer install
```

## Credits

This package was started by [Angry Creative](https://github.com/Angrycreative/composer-plugin-language-update), has been rewritten by [Bjørn Johansen](https://github.com/bjornjohansen/wplang) and integrates compatibility changes made by [Mirai](https://github.com/mirai-wordpress/wplang).
