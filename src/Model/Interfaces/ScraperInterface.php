<?php

namespace App\Model\Interfaces;

interface ScraperInterface
{
    const ALL_SCRAPERS = 'ALL';

    //Main function to scrape web page, bu default we scrape 5 pages (with 20 items per page, we have the 100 items required)
    public function scrape(int $numberOfPages = 5);

    // This function check if car scraped has the valid attributes
    public function isValidCar(iterable $scrapedObject);

    // Format data to persist in database
    public function formatData(iterable $scrapedObject);

    public function getKey(): string ;

    // Get elements to scrape from the list page
    public function getElementsToScrape(int $numberOfPages): void;
}