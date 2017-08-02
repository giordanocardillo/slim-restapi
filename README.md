# Slim RestAPI

A RESTful API based on Slim Framework using exception driven coding.

## Directory Structure

```
+-- app                Contains app files
|  +-- RestAPI         Contains core files
|  |  +-- Exceptions   Contains custom exception classes
|  |  +-- Utils        Contains utilities
|  +-- routes          Contains the Slim routes (all files are included automatically)
|  +-- config.json     Configuration file
+-- public_html        Public web folder
|  +-- index.php       Main slim page
```

## How to configure it

To configure the RestAPI there is a `config.json` file inside the `app` folder. It is self-explanatory.

## How to use it 

This project uses composer, so you can just issue

```bash
composer create-project giordanocardillo/slim-restapi .
```

And you're done!

