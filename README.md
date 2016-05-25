AjglComposerSymlinker
=====================

The AjglComposerSymlinker component provides a simple Composer script to symlink paths from packages installed with
composer to a different location. It is intended for use with web assets.

[![Latest Stable Version](https://poser.pugx.org/ajgl/composer-symlinker/v/stable.png)](https://packagist.org/packages/ajgl/composer-symlinker)
[![Latest Unstable Version](https://poser.pugx.org/ajgl/composer-symlinker/v/unstable.png)](https://packagist.org/packages/ajgl/composer-symlinker)
[![Total Downloads](https://poser.pugx.org/ajgl/composer-symlinker/downloads.png)](https://packagist.org/packages/ajgl/composer-symlinker)
[![Montly Downloads](https://poser.pugx.org/ajgl/composer-symlinker/d/monthly.png)](https://packagist.org/packages/ajgl/composer-symlinker)
[![Daily Downloads](https://poser.pugx.org/ajgl/composer-symlinker/d/daily.png)](https://packagist.org/packages/ajgl/composer-symlinker)
[![License](https://poser.pugx.org/ajgl/composer-symlinker/license.png)](https://packagist.org/packages/ajgl/composer-symlinker)
[![StyleCI](https://styleci.io/repos/8555627/shield)](https://styleci.io/repos/8555627)

This script allows you to install web assets to the `vendor` directory and symlink them to a directory exposed through
an HTTP server, without the need to provide an special composer package type with a custom installer.

Suppose the following project layout where we want to install the `twbs/bootstrap` package:
```
project/
├── src/
│   ├── Controller.php
│   ├── Model.php
│   └── View.php
├── vendor/
└── www/
    ├── assets/
    |   ├── css
    |   ├── fonts
    |   ├── images
    |   └── js
    ├── index.php
    └── .htaccess

```

Any web asset installed with composer will be stored inside the `vendor` directory, but we need them to be stored
inside `www/assets` directory. In the Usage section we will see how to achieve this.

There are some alternatives, but they usually require to define a custom package type in the required package
definition.

Installation
------------

To install the latest stable version of this component, open a console and execute the following command:
```
$ composer require ajgl/composer-symlinker
```


Usage
-----

### 1. Require the source package

Add the desired package to the `require` section of the composer.json file:
```json
{
    "require": {
        "twbs/bootstrap": "^3.3"
    }
}
```

### 2. Define symlinks

Create the symlinks definition adding a `ajgl-symlinks` section inside the `extra` section of the composer.json file:
```json
{
    "extra": {
        "ajgl-symlinks": {
            "twbs/boostrap": {
                "dist/css": "web/assets/css/bootstrap",
                "dist/js": "web/assets/js/bootstrap",
                "dist/fonts/glyphicons-halflings-regular.eot": "web/assets/fonts/glyphicons-halflings-regular.eot",
                "dist/fonts/glyphicons-halflings-regular.svg": "web/assets/fonts/glyphicons-halflings-regular.svg",
                "dist/fonts/glyphicons-halflings-regular.ttf": "web/assets/fonts/glyphicons-halflings-regular.ttf",
                "dist/fonts/glyphicons-halflings-regular.woff": "web/assets/fonts/glyphicons-halflings-regular.woff",
                "dist/fonts/glyphicons-halflings-regular.woff2": "web/assets/fonts/glyphicons-halflings-regular.woff2"
            }
        }
    }
}
```

As you can see, you can link directories or files.

### 3. Hook the script to composer events

Add a new script definition to the `scripts` section of the composer.json file, so the symlinks are created after
packages installation or update:
```json
{
    "scripts": {
        "post-install-cmd": [
            "Ajgl\\Composer\\ScriptSymlinker::createSymlinks"
        ],
        "post-update-cmd": [
            "Ajgl\\Composer\\ScriptSymlinker::createSymlinks"
        ]
    }
}
```

### 4. Execute composer

Once the composer.json file is complete:
```json
{
    "require": {
        "ajgl/composer-symlinker": "^0.2",
        "twbs/bootstrap": "^3.3"
    },
    "extra": {
        "ajgl-symlinks": {
            "twbs/bootstrap": {
                "dist/css": "web/assets/css/bootstrap",
                "dist/js": "web/assets/js/bootstrap",
                "dist/fonts/glyphicons-halflings-regular.eot": "web/assets/fonts/glyphicons-halflings-regular.eot",
                "dist/fonts/glyphicons-halflings-regular.svg": "web/assets/fonts/glyphicons-halflings-regular.svg",
                "dist/fonts/glyphicons-halflings-regular.ttf": "web/assets/fonts/glyphicons-halflings-regular.ttf",
                "dist/fonts/glyphicons-halflings-regular.woff": "web/assets/fonts/glyphicons-halflings-regular.woff",
                "dist/fonts/glyphicons-halflings-regular.woff2": "web/assets/fonts/glyphicons-halflings-regular.woff2"
            }
        }
    },
    "scripts": {
        "post-install-cmd": [
            "Ajgl\\Composer\\ScriptSymlinker::createSymlinks"
        ],
        "post-update-cmd": [
            "Ajgl\\Composer\\ScriptSymlinker::createSymlinks"
        ]
    }
}
```

Open a console and execute the composer update command:
```
$ composer update
```

You will see the following messages in the composer output:
```
> Ajgl\Composer\ScriptSymlinker::createSymlinks
  Symlinking /home/user/project/vendor/twbs/bootstrap/dist/css to /home/user/project/web/assets/css/bootstrap
  Symlinking /home/user/project/vendor/twbs/bootstrap/dist/js to /home/user/project/web/assets/js/bootstrap
  Symlinking /home/user/project/vendor/twbs/bootstrap/dist/fonts/glyphicons-halflings-regular.eot to /home/user/project/web/assets/fonts/glyphicons-halflings-regular.eot
  Symlinking /home/user/project/vendor/twbs/bootstrap/dist/fonts/glyphicons-halflings-regular.svg to /home/user/project/web/assets/fonts/glyphicons-halflings-regular.svg
  Symlinking /home/user/project/vendor/twbs/bootstrap/dist/fonts/glyphicons-halflings-regular.ttf to /home/user/project/web/assets/fonts/glyphicons-halflings-regular.ttf
  Symlinking /home/user/project/vendor/twbs/bootstrap/dist/fonts/glyphicons-halflings-regular.woff to /home/user/project/web/assets/fonts/glyphicons-halflings-regular.woff
  Symlinking /home/user/project/vendor/twbs/bootstrap/dist/fonts/glyphicons-halflings-regular.woff2 to /home/user/project/web/assets/fonts/glyphicons-halflings-regular.woff2
```

### Packages not available at packagist.org

If you want to install a package that is not available in the main composer repository, you can simply define a new
package inside the `repositories` section of the composer.json file.

```json
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "dojo/dojo",
                "version": "1.11.1",
                "dist": {
                    "type": "zip",
                    "url": "http://download.dojotoolkit.org/release-1.11.1/dojo-release-1.11.1.zip"
                },
                "type": "library"
            }
        }
    ]
}
```

Then, you can define the desired symlinks in the `ajgl-symlinks` section as usual:

```json
{
    "extra": {
        "ajgl-symlinks": {
            "dojo/dojo": {
                ".": "www/dojo"
            }
        }
    }
}
```

License
-------

This component is under the MIT license. See the complete license in the [LICENSE] file.


Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker].


Author Information
------------------

Developed with ♥ by [Antonio J. García Lagar].

If you find this component useful, please add a ★ in the [GitHub repository page] and/or the [Packagist package page].

[LICENSE]: LICENSE
[Github issue tracker]: https://github.com/ajgarlag/AjglComposerSymlinker/issues
[Antonio J. García Lagar]: http://aj.garcialagar.es
[GitHub repository page]: https://github.com/ajgarlag/AjglComposerSymlinker
[Packagist package page]: https://packagist.org/packages/ajgl/composer-symlinker
