# DOM Crawler scrapping platform

This project consist in a scraper system, based in Symfony DomCrawler component.
Also, Doctrine ORM is installed to store scraped data in MySql database and have a 
object oriented project.

## Installation

You need have an admin MySql user, by default configured user is *remonvs_paco*, but 
you can change user configuration in *config/parameters.yaml*

* Run *composer install* to install the components required to run that project.

* Run *php bin/console doctrine:database:create* to create database (If your user don't have
permissions to create database, you must create database directly in MySql).

* Run *php bin/console doctrine:schema:update --force* to install the database schema.

## Usage

By default this project has two scrapers *autoScout24* and *dummy*(this scraper is for test,
use this like example)

To run a scraper, type: *php bin/console app:scraper* in project root folder,
this command admit 3 parameters **All parameter are optional**
* *--scraper* : With this parameter you can select what scraper want execute, if you don't
select any scraper, the command execute all scrapers configured (by default both named previously)
Usage example *php bin/console app:scraper --scraper=auto_scout*. The name of the scraper is defined
in *KEY* constant variable inside scraper file. (src/Service/Commands/*)
* *--start-page* : With this parameter you can select which page to start to scrape by default, star page is 1.
Usage example *php bin/console app:scraper --scraper=auto_scout --start-page=5*
* *--number-of-pages* : With this parameter you can select the number of pages scraped, by default scrap 5 pages.
Usage example *php bin/console app:scraper --scraper=auto_scout --start-page=5 --number-of-pages=10* (This command run
auto_scout scraper starting in page number 5 and ending in page 14)

