# Laravel DB Packer Tool
This tool develop to make auto migrations your all tables in database included the data.

So you can easily make a collaboration wih your team in GIT, or you can publish your project easily without any sparate database file.

## How To Install
1. ``composer require crocodic-studio/laravel-db-packer``
1. Put this ``Crocodic\LaravelDBPacker\LaravelDBPackerServiceProvider::class`` to /config/app.php at ``Providers`` section.

## How To Pack
1. ``php artisan packer:pack``

## How To Unpack
1. ``php artisan packer:unpack``

### Credits
Laravel Migrations Generator 
https://github.com/Xethron/migrations-generator