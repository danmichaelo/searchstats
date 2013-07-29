Simple usage statistics for [Realfagsbiblioteket app](//github.com/saqimtiaz/BibSearch).

Database schema:

    CREATE TABLE `visits` (
    `id` int(9) UNSIGNED NOT NULL AUTO_INCREMENT,
    `timestamp` datetime NOT NULL,
    `user_agent` tinytext NULL,
    `accept_lang` tinytext NULL,
    `app_version` varchar(8) NULL DEFAULT NULL,
    PRIMARY KEY (`id`) 
    )

Copy `config.dist.yml` to `config.yml` and configure for your server.

Currently deployed [here](http://linode.biblionaut.net/app/).

