
# HaveIVoted.org

Let's rock the vote!

## Running

    docker-compose up -d

## Installation

    sudo -u postgres createuser phppro-voted --password
    sudo -u postgres createdb --owner=phppro-voted haveivoted
    echo 'ALTER USER "phppro-voted" WITH PASSWORD '"'"'<The DB Password>'"'"'' | sudo -u postgres psql

Put the DB password into .env

    composer install
    php artisan key:generate
    php artisan migrate

