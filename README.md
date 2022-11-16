## Project Specifications

**Environment**  

- PHP version: 8.0
- Laravel version: 8.75
- Default Port: 9090

**Commands**
- install: 
```bash
composer install
```
- run: 
```bash
php artisan migrate --seed
```
- or restart all: 
```bash
php artisan migrate:refresh --seed
```

```bash
php artisan serve --host=0.0.0.0 --port=9099
```

- test: 
```bash
php artisan test
```
