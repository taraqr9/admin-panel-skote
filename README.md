# Laravel Project Installation Guide

## Project Built With

- PHP ^8.4
- Laravel Framework ^13.7
- Laravel Tinker ^3.0
- Spatie Laravel Permission ^7.4
- Spatie Laravel Activitylog ^5.0
- Lab404 Laravel Impersonate ^1.7
- SKOTE Admin Theme

## Requirements

Make sure your system has the following installed:

- PHP 8.4 or higher
- Composer
- MySQL
- Git

## Installation Steps

Clone the project:

```bash
git clone <repository-url>
cd <project-folder>
```

## Install PHP dependencies:
```
composer install
```

## Copy the environment file:
```
cp .env.example .env
```

## Generate application key:
```
php artisan key:generate
```

## Run migration
```
php artisan migrate --seed
```

## Use the seeded admin account: <br><br>
User: admin <br>
password: password