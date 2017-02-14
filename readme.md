# Rospdf Laravel

Rospdf PHP integration for Laravel

## Installation  

Install from composer :  
`composer require lvieira/rospdf-laravel`  

In config/app.php file, adds the Provider:

```php
       'providers' => [
            ...
            
            /*
             * Rospdf Service Provider
             */
            Vieira\Rospdf\RospdfServiceProvider::class
       ]
```

and the Facade: 

```php
       'alias' => [
            ...            
            'Rospdf' => Vieira\Rospdf\Facades\Rospdf::class
       ]
```

Now the package is ready for use !