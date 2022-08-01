# facebook-pages-content-share
This application is made using Laravel 9 and livewire components. It is about using the Facebook graph API, and giving the user the functionalities to manage their Facebook Pages and the content they want to share in them directly from our app.

# Instruction to install the application
```
git clone ...
cd facebook-pages-content-share
composer install
npm install
php artisan migrate
npm run dev
php artisan serve
```
> Don't forget to copy the env file and enter a valid database name before migration

# usage
```
npm run dev
php artisan serve
php artisan schedule:work
```
> Create a new account, then connect your facebook account.

# Functionalities
- Connect/Deconnect facebook account.
- User get notified each time a post is shared using his account.
- Users get notified weekly about the number of posts shared.
- Share immediate posts on Facebook pages.
- Share scheduled posts on Facebook pages.
- Share text content or media content like images, video ( will be added soon )
