# P6-Snowtricks

This is a community blog related to Snowboard tricks. Users can manage the articles once they are created their account and log in. 
It is made with Symfony framework without extern bundles.

[![Maintainability](https://api.codeclimate.com/v1/badges/bd1406aaf0649a2cd1f9/maintainability)](https://codeclimate.com/github/EdwigeGC/P6-Snowtricks/maintainability)

## Getting started
### Prerequisites

Installation of the project, requires:

  *  PHP version 7.4.12
  *  HTML 5
  *  CSS 3c
  *  Bootstrap 5.0
  *  MySQL version 5.7
  *  Apache Server 2.4.46
  *  Composer 2.0
  *  Twig 3.2.1
  *  Mailer 5.2.1
  *  Doctrine/ORM 2.8.1
  *  phpDocumentor 5.2.2

### Installation
 1. Copy the link on GitHub and clone it on your local repository
 2. Open your terminal and run:
   ``` composer install```
 3. Create database: 
  ```php bin/console doctrine:database:create```
 4. Open file .env end # customize this line:
```DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"```

## Features
### What can do all users
  *  consult the trick's list
  *  read a trick details
  *  create an account
  *  log in or reset his password (if he had created a user account first)
  *  read the comment in chats room for each tricks
 
### What can do an authenticated user
  *  add a trick
  *  edit a trick
  *  delete a trick
  *  write a comment
  *  edit his account information


## Credit
logo: Canva.com
