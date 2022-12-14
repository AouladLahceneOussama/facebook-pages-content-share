# facebook-pages-content-share
This application is made using Laravel 9 and livewire components. It is about using the Facebook graph API, and giving the user the functionalities to manage their Facebook Pages and the content they want to share in them directly from our app.

# Instruction to install the application
```
git clone ...
cd facebook-pages-content-share
git checkout master
composer install
npm install
php artisan migrate
```
> Don't forget to copy the env file and enter a valid database name before migration.  
> After creating .env file please run this command to generate an application key 
```
php artisan key:generate
```
> The last thing to do is to insert the Facebook keys into the .env file.  
> and the smtp mail trap to catch emails
```
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT=http://localhost:8000/connect/facebook/callback
```

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
- Manage the comments(Share new comments/ Reply on other comments / Delete)
