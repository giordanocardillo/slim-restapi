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

## How to get it running

This project uses composer, so you can just clone it, then

```bash
composer install
```

And you're done!
