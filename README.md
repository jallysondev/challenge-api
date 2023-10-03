# Challenge API by [Coodesh](https://coodesh.com/)

## Target

-   https://coodesh-challenge-api.mjsolutions.space/api

## Stack summary

-   [PHP](https://www.php.net/docs.php)
-   [Laravel](https://laravel.com/)
-   [Pest](https://pestphp.com/docs)
-   [MySQL](https://dev.mysql.com/doc/)

### Third-party

-   [Open Food Facts](https://br.openfoodfacts.org/data)

## Instalation

### Before install application:

1.  Check if your PHP version is equal to or greater than 8.2

### Create a copy of your .env.example file

    cp .env.example .env

### After above step install project dependencies:

    composer install

### After the above step, up the migrations.:

    php artisar migrate

### Finally, to ensure that there will be no problem with the key, the application executes the command to recreate it:

    php artisan key:generate

Generally, the application is started in [http://localhost:8000](http://localhost:8000) (check terminal info)

### For more information access:

## https://laravel.com/

This is a challenge by [Coodesh](https://coodesh.com/).
