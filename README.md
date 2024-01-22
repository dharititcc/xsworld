# XS World

XS World looks to revolutionise the bar and club experience for customers by providing a seamless and efficient way to order and pickup drinks through our app.


## Demo

Here is the url for the [demo](https://xsworld.online/).

## Deployment

To deploy this project run:
Please clone this repository then following steps:

* Run ```composer install```.
* Copy ```.env.example``` to ```.env``` Example for linux users : ```cp .env.example .env```
* Set valid database credentials of env variables ```DB_DATABASE```, ```DB_USERNAME```, and ```DB_PASSWORD```
* Run ```php artisan key:generate``` to generate application key
* Run ```php artisan storage:link```
* Run ```php artisan migrate:fresh --seed``` command to generate tables in the database
* Start running project using ```php artisan run```

## Authors

- [Milan Soni](https://github.com/milanitcc)
- [Arshit Goti](https://github.com/arshititcc)
- [Athar](https://github.com/atharitcc)
- [Dharit](https://github.com/dharititcc)