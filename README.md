# mirai-wordpress/wplang

Composer plugin to download translation files for WordPress core, plugins and themes from wordpress.org. Forked from bjornjohansen/wplang.

## Installation

First run:

```
composer require mirai-wordpress/wplang
```

Edit your `composer.json` file, and add this lines at the following section:
```
"extra": {
    "wordpress-languages": [ "en_GB", "nb_NO", "sv_SE" ],
    "wordpress-language-dir": "web/app/languages"
}
```

Customize the languages and language dir to suite your needs.

Finally run:
```
composer update
```

Now Composer will try to pull down translations for all your packages from wordpress.org every time you install or update a package.

## Credits

This package Started as a fork of Angry Creative’s [Composer Auto Language Updates](https://github.com/Angrycreative/composer-plugin-language-update), but has since been rewritten. It is not compatible with the original package at all, but this package would probably not have existed with the first. There are probably some code in this package that the original author will still recognize. Last [fork](https://github.com/mirai-wordpress/wplang) for compatibility changes made by [Mirai](https://mirai.com).
