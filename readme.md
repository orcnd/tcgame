# tcgame project

## install

1. clone the repo
2. configure mysql settings in `config.php`
3. run `composer install` it also install database tables
4. run `composer test` to run tests
5. run `php -S localhost:4000 -t public`

### tricks

- if you wish to reinstall, remove the vendor directory and run `composer install` again
