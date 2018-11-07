# Laravel DB Packer Tool
This tool develop to make auto migrations your all tables in database included the data.

So you can easily make a collaboration wih your team in GIT, or you can publish your project easily without any sparate database file.

## How To Install
1. ``composer require crocodic-studio/laravel-db-packer``
1. Put this ``Crocodic\LaravelDBPacker\LaravelDBPackerServiceProvider::class`` to /config/app.php at ``Providers`` section.

## How To Pack
``php artisan packer:pack``
## Pack with table exception
``php artisan packer:pack --ignoreTable="table1,table2,table3"``
## Pack with table data exception
``php artisan packer:pack --ignoreTableData="table1,table2,table3"``

And you may combine two options.
## How To Unpack 
This command will restore tables and data

``php artisan packer:unpack``

### Credits
Laravel Migrations Generator 

https://github.com/Xethron/migrations-generator