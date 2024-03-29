<!-- <br/>

<p>
	<img src="https://onricatech.com/img/logo.png" width="200" />
</p>

<br/> -->

## About Onrica SmartWork

**Onrica SmartWork** is a multi-tenant ERP solution.

With its number of modules & features, **Onrica SmartWork** allows businesses to achieve a high level of synchronization among departments and units of a business.

##### Characteristics:

-   Monolithic Architecture
-   Full Multi-tenant Architecture (i.e. single database and single app instance)
-   PWA App

## Frameworks & Tools Used

At its core [**Onrica SmartWork**](https://onricatech.com/products/smartwork) uses [**Laravel**](https://laravel.com) as a fullstack framework.

| Frontend Tools     | Backend Tools                           | Other Tools  |
| ------------------ | --------------------------------------- | ------------ |
| Bulma              | Laravel                                 | Laravel Pint |
| Font Awesome Icons | Livewire                                |
| jQuery             | Laravel DomPDF                          |
| AlpineJS           | Doctrine Dbal                           |
| Axios              | Spatie - Laravel Permission             |
| jQuery DataTables  | Yajra - Laravel DataTables              |
| Summernote Editor  | Laravel Debugbar                        |
| Select2 Dropdown   | LaraBug                                 |
| Sweetalert         | Flysystem Google Drive                  |
| Pace.js            | Spatie - Laravel Backup                 |
| Workbox            | laravel-cascade-soft-deletes            |
|                    | Maatwebsite - Laravel Excel             |
|                    | Larave Notifications Channel - Web Push |

## Branches

-   main
    -   This is the branch that runs on the production server
    -   Should not be forked
    -   Do not submit PR to this branch
-   dev
    -   Always use this branch for development and making changes
    -   Could be forked
    -   Always submit PRs to this branch

## Requirements

-   PHP >= 8.0
-   RAM >= 2GB
-   Composer
-   MySQL or MariaDB
-   [PHP extensions required by Laravel](https://laravel.com/docs/9.x/deployment#server-requirements)
-   [OPTIONAL] To make use of PWA capabilities, install browsers that support **Service Workers** and **Add To Home Screen** from the links below:
    -   [Browsers that support Service Workers](https://caniuse.com/?search=service%20worker)
    -   [Browsers that support website installation](https://caniuse.com/?search=a2hs)
    -   **Recommended Browser: Chrome (both on mobile and desktop)**

## Installation

```bash
git clone https://github.com/onrica/smartwork.git
cd smartwork
composer install
cp .env.example .env
php artisan key:generate
```

Go to the root folder (i.e. smartwork), find .env file and set the values of the following: DB_DATABASE, DB_USERNAME, and DB_PASSWORD.

```bash
php artisan migrate --seed
php artisan serve
```

## Login Credentials

| Email            | Password |
| ---------------- | -------- |
| admin@onrica.com | password |
