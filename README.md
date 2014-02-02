Simple usage statistics for [Realfagsbiblioteket app](//github.com/saqimtiaz/BibSearch).
Currently deployed [here](//bibapp.biblionaut.net/).

Setup:

    curl -sS https://getcomposer.org/installer | php
    php composer.phar update

Copy `config.dist.yml` to `config.yml` and configure for your server.

Database schema:

    CREATE TABLE `visits` (
      `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
      `timestamp` datetime NOT NULL,
      `user_agent` tinytext NOT NULL,
      `accept_lang` tinytext NOT NULL,
      `app_version` varchar(8) NOT NULL,
      `request_time` int(6) NOT NULL,
      `cql` tinytext NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

