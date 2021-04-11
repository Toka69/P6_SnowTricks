# P6_SnowTricks

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/55249ac7b1cd4c7e9c94cdda7117f7ab)](https://www.codacy.com/gh/Toka69/P6_SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Toka69/P6_SnowTricks&amp;utm_campaign=Badge_Grade)


[![Maintainability](https://api.codeclimate.com/v1/badges/01a06e10947fe6133340/maintainability)](https://codeclimate.com/github/Toka69/P6_SnowTricks/maintainability)

This is the sixth project in my Symfony developer journey. It's about making a community snowboard tricks site.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

- PHP 8.0.1
- Symfony 5.2
- Symfony CLI
- Composer

Developed and tested with MariaDB, in this case the following PHP extensions are necessary:

- pdo_mysql extension
- mysqli extension

### Installing

A step by step series of examples that tell you how to get a development env running

1) Clone the project in your workspace of your PHP environment.
2) Install the necessary libraries via composer
   ```
   php composer install
   ```
3) Copy the .env file to .env.local and change the settings according to your needs. The parameters present in .env.local overwrite those found in .env
4) Create the database
   ```
   php bin/console doctrine:database:create
   ```
5) Make a migration and migrate it
   ```
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate
   ```
6) Load fixtures
   ```
   php bin/console doctrine:fixtures:load
   ```
7) Run the symfony web server
   ```
   symfony server:ca:install
   symfony server:start
   ```
8) It's ready!

### Docker

If you want to use a ready container for this project you can build the docker-compose inside the "build" directory. Previously, you can
change the settings according to your needs.
If you are using a MySQL / MariaDB database, make sure they are on the same docker network. Here it is the "my-network" network, you can change it in the docker-compose file.

To build it:
   ```
   /build/docker-compose up -d
   ```

### Accounts existing

- admin@gmail.com
- jgoldman@gmail.com
- edurand@gmail.com
- edupont@gmail.com

All accounts have the same password: Administrateur8!