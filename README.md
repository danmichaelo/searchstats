Simple usage statistics for [Realfagsbiblioteket app](//github.com/saqimtiaz/BibSearch).
Currently deployed [here](http://linode.biblionaut.net/app/).

If you don't have YAML;

    sudo apt-get install libyaml-dev
    sudo pecl install yaml

Setup:

    curl -sS https://getcomposer.org/installer | php
    php composer.phar update

Copy `config.dist.yml` to `config.yml` and configure for your server.

Database schema:

    CREATE TABLE `visits` (
    `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    `timestamp` datetime NOT NULL,
    `user_agent` tinytext NULL,
    `accept_lang` tinytext NULL,
    `app_version` varchar(8) NULL DEFAULT NULL,
    PRIMARY KEY (`id`) 
    )

