ZF2+Doctrine example Application
================================

Example of ZF2 + Doctrine application.

[Demo](http://zf2-demo.makeyoulivebetter.org.ua/admin/login)

User: `jane.doe@makeyoulivebetter.org.ua`

Pass: `demo`

**Note:** Application active development.

Features
--------

[Bootstrap 3](https://getbootstrap.com/)

[AdminLTE theme](https://almsaeedstudio.com/) for admin pages

ToDo
----

- [x] Articles admin pages (list, add, edit)
- [ ] User admin pages (list, add, edit, view)
- [ ] ACL, user roles and pemissions
- [ ] Fix password hash generating
- [ ] Move file save path to config per entity
- [ ] Drag-n-drop upload, multi-upload

Installation
------------

    git clone git@github.com:zviryatko/blog-example.git blog-example
    cd blog-example

#### Phing

Install phing with git support:

    composer global require phing/phing
    
And install via:

    phing install

#### By-Hand

    composer update
    mysql -u root -p -e "CREATE DATABASE zf2_blog CHARACTER SET utf8 COLLATE utf8_general_ci;"
    cp config/autoload/doctrine.orm.local.php.dist config/autoload/doctrine.orm.local.php
    vim config/autoload/doctrine.orm.local.php
    ./vendor/bin/doctrine-module orm:schema-tool:create


Web server setup
----------------

### PHP CLI server

#### PhpStorm

Go to `Run > Edit Configurations`, click on green sign and fill like in screenshot:

![PhpStorm run configurations](https://dl.dropboxusercontent.com/u/12457762/screenshot/screenshot-2015-10-04_11%3A22%3A24.png)

After saving start web server `Run > Run 'Local w/Xdebug'`.

#### Command-line

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root
directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note:** The built-in CLI server is *for development only*.
