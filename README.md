# NOBI Recruitment Test - Backend Engineer

## About
This is an aplication for recruitment test as Backend Engineer from NOBI. This is an application to allow user to invest in NOBI product.

### Flow
When user invest in a product. User balance will converted to unit. Unit that user got is calculate by ```User Balance / NAB```. Value of NAB is calculate using ```Total Users Balace / Total Users Unit```. If that product didn't have any users, the NAB value will be ```1```. Users will always see their balance and their balance will calculate using ```Unit / NAB```

### Feature
- Top Up
- Withdraw

## Setup
- Clone repo
- Go to repo directory
- Run ```composer install```
- Run ```cp .env.example .env```
- Run ```php artisan key:generate```
- Edit database credentials in .env file
- Run ```php artisan migrate```
- Run ```php artisan test``` to test it using Pest
- Import Postman Collection from ```NOBI.postman_collection.json``` file
- You also can import database from ```database.sql```