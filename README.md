
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

## ChangeLog

### v1.5.0 @ 2020-11-02

* **[2020-11-02 03:22:36 CST]** - Added functionality for parsing The State of Washington's voter rolls.

### v1.1.0 @ 2020-11-02

* **[2020-11-01 13:49:02 CST]** - Added duplicated Texas votes.
* **[2020-11-01 13:57:06 CST]** - Sorted duplicate votes by county, name.
* **[2020-11-01 14:03:49 CST]** - Added duplicate counties stats.
* **[2020-11-01 14:50:01 CST]** - Added Party Affiliation to the duplicate counties stats.
* **[2020-11-01 14:56:28 CST]** - Added Dupe Votes by County stats.
* **[2020-11-01 15:10:45 CST]** - Record the Texas 2016 election results data.
* **[2020-11-01 18:10:33 CST]** - Updated the duplicated votes to sort by voter_id.
* **[2020-11-01 18:54:20 CST]** - Probable fraud analysis of the Texas 2020 Early Voting rolls.
* **[2020-11-01 22:50:49 CST]** - Added Facebook Image and Description.
* **[2020-11-01 22:51:49 CST]** - [m] Some ASCIIArt.
* **[2020-11-02 00:12:38 CST]** - Display dump-related stats.
* **[2020-11-02 00:13:11 CST]** - [Major] Completely refactored [flattened] how county names are stored in the database.
