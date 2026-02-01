# Payment and order managment API

## Setup
### First in `php.ini` uncommend
```
extension=sodium
```

### Install packages

```
composer install
```

### Generate key for JWT

```
php artisan jwt:secret
```

### Run migration

```
php artisan migrate
```

### Run the application

```
php artisan serve
```

### Run the queue

```
php artisan queue:work
```