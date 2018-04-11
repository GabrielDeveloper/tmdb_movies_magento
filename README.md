# The Movies Database (TMDB) Module for Magento2

### Installation

TMDB_Movies requires [Magento](https://github.com/magento/magento2) v2+ to run.

- Install Magento 2
- Send the directory TMDB/ to path magento2/app/code/

##### Install the module

 View disabled modules:
```sh
$ php bin/magento module:status
```

 Enable module:

```sh
$ php bin/magento module:enable -c TMDB_Movies
$ php bin/magento setup:upgrade
$ php bin/magento setup:di:compile
```

## How to use:

 * There is an icon on sidebar menu to TMDB Movies screen

 * Enter to this menu and see the movies ordered by popularity.

 * To add a movie to your store, select the movie and you'll be redirected to the page to add the price of this movie.

 * Choose the price of the movie and click to add as a product
