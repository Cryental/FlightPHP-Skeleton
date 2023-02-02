# FlightPHP Framework Skeleton

This is the skeleton of FlightPHP framework with Eloquent ORM, Twig and Whoops error handler.

### Run Dev Server
```
php -S localhost:8000 public/index.php
```

### Run Schedule
```
vendor/bin/crunz schedule:run
```

### Run Migration
```
# Init Migrate
php bin/migrations.php migrate

# Remove All and Migrate Again
php bin/migrations.php migrate:fresh
```