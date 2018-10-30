# amman.fotobe.ch Docker

docker-compose-port von amman.fotobe.ch

## Installation

Alles installieren und starten: `make`<br>
Dies geht davon aus dass alle web-sourcen unter
`source/web/` und ein Datenbankdump unter `source/db_import/amman.sql` liegt.

Diese Daten können zuvor mit `make fetch-sources` vom prod-server
geholt werden.

## Ports

Die Webapp ist unter Port `8030` und MariaDB unter Port `4030` und phpmyadmin unter `3030` verfügbar.


## Konfiguration

Alle Relevanten Einstellungen können im File `settings.env` angepasst werden.


## wp-upgrade (notes)

```

# apply "patch" to wordpress
cp files/functions-menus.php wp-content/themes/fotobuero15/includes/functional/

# container needs to be on php7.0-apache for upgrading
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
chmod +x /usr/local/bin/wp
alias wp="wp --allow-root"

# add user for accessig webinterface
wp user create eni eni@email.ch --role=administrator --user_pass=123qwe --send-email=false

# upgrade core to latest version
wp core update

# login to webinterface to say yes to db migrations

# upgrade plugins and lang to latest version
wp plugin update --all
wp theme update --all
wp language core update
wp language plugin update --all

# stop container and upgrade to latest php version

```
