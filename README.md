# BileMo API
BileMo is a company offering a variety of premium mobile phones.
***
*[![Maintainability](https://api.codeclimate.com/v1/badges/646da8779fa04f5474b2/maintainability)]
*(https://codeclimate.com/github/Djiek/BileMoApi/maintainability)
***
## Technologies
***
A list of technologies used within the project:
* [Symfony]: Version 5.2
* [PHP]: Version 7.3.12
* [Doctrine]
* [git]  
* [mySql] 
* [composer]
***

## Documentation technique 
ex : http://localhost:8000/api/doc

## Installation
***
BileMo API requires php 7.3.12 to run.
To install the project :

* To download the project, please clone the github project with the repository link :
```$ git clone https://github.com/Djiek/BileMoApi```
* Update your BDD credentials in BileMo .env
```
$ composer install
$ php bin/console doctrine:database:create 
$ php bin/console doctrine:migrations:migrate
$ php bin/console doctrine:fixtures:load 
$ openssl genrsa -out config/jwt/private.pem 4096",
$ openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem",
$ php bin/console server:run
```
* Password for authentification for generate token : "123" (String)
* username for authentification : email in customer table
* exemple : {"username": "mail@gmail.fr", "password": "123"} 
