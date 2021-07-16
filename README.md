# goGospel Back-end API

## Code overview
- laravel 8.0
- php": "^7.3|^8.0"

### Authentication 

This application uses laravel sanctum & fortify to handle authentication.

## Installation

Clone the repository

    git clone https://github.com/AeroboDrones/gogospel-be.git
    
Switch to the repo folder

    cd gogospel-be
    
Install all the dependencies using composer
    
    composer install

Copy the example env file and make the required configuration changes in the .env file.

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (Set the database connection in .env before migrating)

    php artisan migrate

Start the local development server

    php artisan serve
