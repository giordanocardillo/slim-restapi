# Slim RestAPI

[![Code Climate](https://img.shields.io/codeclimate/github/giordanocardillo/slim-restapi.svg?style=flat-square)](https://codeclimate.com/github/giordanocardillo/slim-restapi)
[![Codacy Grade](https://img.shields.io/codacy/grade/21af478546124c77adee67956ac35579.svg?style=flat-square)](https://www.codacy.com/app/giordanocardillo/slim-restapi)
[![Packagist](https://img.shields.io/packagist/dt/giordanocardillo/slim-restapi.svg?style=flat-square)](https://packagist.org/packages/giordanocardillo/slim-restapi)

A RESTful API based on Slim Framework using exception driven coding.

## Directory Structure

```text
+-- app                Contains app files
|  +-- RestAPI         Contains core files
|  |  +-- Exceptions   Contains custom exception classes
|  |  +-- Utils        Contains utilities
|  +-- routes          Contains the routes
|  +-- config.json     Configuration file
+-- public_html        Public web folder
|  +-- index.php       Main slim page
+-- database.sql        The database structure file   
```

## How to configure it

To configure the RestAPI there is a `config.json` file inside the `app` folder. It is self-explanatory.

## How to use it 

This project uses composer, so you can just issue

```bash
composer create-project giordanocardillo/slim-restapi .
```

Then import the DB using the provided `database.sql` file

## User authentication workflow

TODO
