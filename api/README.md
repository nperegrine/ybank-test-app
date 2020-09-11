> Laravel Backend API for our yBank

## Installation
1.) Install all composer dependencies by running:
```bash
composer install
```
2.) Add Laravel .env file to your project root and generate your APP_KEY by running:
```bash
php artisan key:generate
```
3.) Run your database migrations and seed the database
```bash
php artisan migrate
php artisan db:seed
```
4.) You are now ready to launch your API. Do this by running:
```bash
php artisan serve
```
## Remarks
The API works, I did my best within the given period of time to fix and finish the most common edge cases, I'm aware of more UX issues but lets put 'em in the backlog for now, lets ship—i know there's more work to make this great.
