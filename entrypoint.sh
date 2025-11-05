#!/bin/bash

set -e

until php -r "new mysqli(getenv('MEDIAWIKI_DB_HOST'), getenv('MEDIAWIKI_DB_USER'), getenv('MEDIAWIKI_DB_PASSWORD'), getenv('MEDIAWIKI_DB_NAME'));" 2>/dev/null; do
    sleep 2
done

php maintenance/run.php update --no-interactive

exec apache2-foreground
